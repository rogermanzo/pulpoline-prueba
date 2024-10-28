const User = require('../models/User');

// Registro de usuario
exports.register = async (req, res) => {
  const { username, password } = req.body;

  const newUser = new User({ username, password });
  try {
    await newUser.save();
    res.status(201).send('Usuario registrado');
  } catch (error) {
    res.status(400).send('Error al registrar el usuario');
  }
};

// Autenticación de usuario
exports.login = async (req, res) => {
  const { username, password } = req.body;

  const user = await User.findOne({ username, password });
  if (!user) {
    return res.status(401).send('Credenciales incorrectas');
  }

  // Generar y devolver token aquí

  res.send('Usuario autenticado');
};
