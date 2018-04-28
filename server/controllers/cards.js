const Cards = require('../models').Cards;

module.exports = {
  create(req, res) {
    return Cards
      .create({
        name: req.body.name,
        character: req.body.character,
        cost: req.body.cost,
        type: req.body.type,
        rarity: req.body.rarity,
        description: req.body.description,
        upgrade_description: req.body.upgrade_description
      })
      .then(card => res.status(201).send(card))
      .catch(error => res.status(400).send(error));
  },
  list(req, res) {
    return Cards
      .all()
      .then(cards => res.status(200).send(cards))
      .catch(error => res.status(400).send(error));
  },
  names(req, res) {
    return Cards
      .all()
      .then(cards => res.status(200).send(cards.map(element => { return element.name; })))
      .catch(error => res.status(400).send(error));
  },
  getCardById(req, res) {
    return Cards
      .findById(req.params.id)
      .then(card => {
        if (!card) {
          return res.status(404).send({
            message: 'Card does not exist.',
          });
        }

        return res.status(200).send(card);
      })
      .catch(error => res.status(400).send(error));
  },
  getCardByName(req, res) {
    return Cards
      .findOne({ where: {name: req.params.name}})
      .then(card => {
        if (!card) {
          return res.status(404).send({
            message: 'Card does not exist.',
          });
        }

        return res.status(200).send(card);
      })
      .catch(error => res.status(400).send(error));
  },
  search(req, res) {
    return Cards
      .findOne({ where: {name: req.body.name}})
  }
};