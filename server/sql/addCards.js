const Cards = require('../models').Cards;
const data = require('./sample');

let add = (name, character, cost, type, rarity, description, upgrade_description) => {
	Cards.create({
	    name: name,
	    character: character,
	    cost: cost,
	    type: type,
	    rarity: rarity,
	    description: description,
	    upgrade_description: upgrade_description
	})
	 .then(card => console.log(JSON.stringify(card, null, 2)))
   	 .catch(error => console.log(error));
};

for (let key in data) {
	let obj = data[key];

	add(obj.name, obj.character, obj.cost, obj.type, obj.rarity, obj.description, obj.upgrade_description);
}