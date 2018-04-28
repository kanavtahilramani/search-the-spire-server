const cardsController = require('../controllers/cards');

module.exports = (app) => {
  app.get('/api/cards', cardsController.names);
  app.post('/api/cards', cardsController.create);
  app.get('/api/cards/id/:id', cardsController.getCardById);
  app.get('/api/cards/:name', cardsController.getCardByName);
};