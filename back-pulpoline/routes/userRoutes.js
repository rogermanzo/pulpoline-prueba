const { Client, TokenCreateTransaction } = require('@hashgraph/sdk');
require('dotenv').config();

const express = require('express');
const jwt = require('jsonwebtoken');
const bcrypt = require('bcryptjs');

const User = require('../models/User');
const Token = require('../models/Token');

const router = express.Router();

// Clave secreta para JWT
const JWT_SECRET = process.env.JWT_SECRET || '3b5defb9f40beeed1ce8c8baad1a772490225b3201567702230886bf38eaaef5';

const accountId = process.env.HEDERA_ACCOUNT_ID;
const privateKey = process.env.HEDERA_PRIVATE_KEY;

if (!accountId || !privateKey) {
    throw new Error('Debes definir HEDERA_ACCOUNT_ID y HEDERA_PRIVATE_KEY en tus variables de entorno');
}

// Configura el cliente de Hedera
const client = Client.forTestnet();
client.setOperator(accountId, privateKey);

const authMiddleware = (req, res, next) => {
    const token = req.headers['authorization'];
    if (!token) return res.status(403).send('Token es requerido');

    const bearerToken = token.split(' ')[1];
    jwt.verify(bearerToken, JWT_SECRET, (err, user) => {
        if (err) return res.sendStatus(403);
        req.user = user; // Guarda la información del usuario en la solicitud
        next();
    });
};


// Ruta para registrar un usuario
router.post('/register', async (req, res) => {
    const { username, password } = req.body;

    try {
        let user = await User.findOne({ username });
        if (user) {
            return res.status(400).json({ message: 'Usuario ya existe' });
        }

        const hashedPassword = await bcrypt.hash(password, 10);
        user = new User({ username, password: hashedPassword });
        await user.save();

        const token = jwt.sign({ id: user._id }, JWT_SECRET, { expiresIn: '1h' });
        res.status(201).json({ token });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
});

// Ruta para iniciar sesión
router.post('/login', async (req, res) => {
    const { username, password } = req.body;

    try {
        const user = await User.findOne({ username });
        if (!user) {
            return res.status(400).json({ message: 'Credenciales inválidas' });
        }

        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(400).json({ message: 'Credenciales inválidas' });
        }

        const token = jwt.sign({ id: user._id }, JWT_SECRET, { expiresIn: '1h' });
        res.json({ token });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
});

// Ruta para cerrar sesión
router.post('/logout', (req, res) => {
    res.json({ message: 'Logout exitoso' });
});

// Ruta para crear un nuevo token en Hedera
router.post('/create-token-hedera', authMiddleware, async (req, res) => {
    const { name, symbol, initialSupply } = req.body;

    try {
        const transactionResponse = await new TokenCreateTransaction()
            .setTokenName(name)
            .setTokenSymbol(symbol)
            .setDecimals(0)
            .setInitialSupply(initialSupply)
            .setTreasuryAccountId(accountId)
            .execute(client);

        const receipt = await transactionResponse.getReceipt(client);
        const tokenId = receipt.tokenId.toString();

        // Guardar el token en la base de datos
        const newToken = new Token({
            tokenId,
            name,
            symbol,
            initialSupply,
            userId: req.user.id
        });
        await newToken.save();

        res.status(201).json({ tokenId, name, symbol, initialSupply });
    } catch (error) {
        console.error('Error al crear el token:', error);
        res.status(500).json({ error: error.message });
    }
});

// Ruta para listar tokens
router.get('/list-tokens', authMiddleware, async (req, res) => {
    try {
        const tokens = await Token.find({ userId: req.user.id }); // Filtra por el ID del usuario autenticado
        res.json(tokens);
    } catch (error) {
        console.error('Error al listar los tokens:', error);
        res.status(500).json({ error: error.message });
    }
});

// Exportar el router
module.exports = router;
