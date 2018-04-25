const cardsController = require('../controllers/cards');

module.exports = (app) => {
  app.get('/api', (req, res) => res.status(200).send({
    message: 'Hello world!',
  }));

  app.get('/api/cards', cardsController.list);
  app.get('/api/cards/:id', cardsController.retrieve);
  app.post('/api/cards', cardsController.create);
};