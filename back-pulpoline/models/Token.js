// models/Token.js
const mongoose = require('mongoose');

const tokenSchema = new mongoose.Schema({
    tokenId: { type: String, required: true },
    name: { type: String, required: true },
    symbol: { type: String, required: true },
    initialSupply: { type: Number, required: true },
    userId: { type: mongoose.Schema.Types.ObjectId, ref: 'User' } // Relaciona el token con el usuario
});

module.exports = mongoose.model('Token', tokenSchema);
