const express = require('express');
const cors = require('cors');
const app = express();

app.use(cors());
app.use(express.json());

app.post('/api/usuarios', (req, res) => {
  const { nome, email, senha } = req.body;
  console.log('Usuário recebido:', { nome, email, senha });
  return res.status(201).json({ mensagem: 'Usuário cadastrado com sucesso!' });
});

app.listen(5000, () => {
  console.log('Servidor rodando em http://localhost:5000');
});
