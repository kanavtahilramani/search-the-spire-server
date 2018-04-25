'use strict';
module.exports = (sequelize, DataTypes) => {
  const Cards = sequelize.define('Cards', {
    name: {
      type: DataTypes.STRING,
      allowNull: false
    },
    character: {
      type: DataTypes.STRING,
      allowNull: true
    },
    cost: {
      type: DataTypes.STRING,
      allowNull: false
    },
    type: {
      type: DataTypes.STRING,
      allowNull: false
    },
    rarity: {
      type: DataTypes.STRING,
      allowNull: false
    },
    description: {
      type: DataTypes.STRING,
      allowNull: false
    },
    upgrade_description: {
      type: DataTypes.STRING,
      allowNull: false
    }
  }, {});
  Cards.associate = function(models) {
    // associations can be defined here
  };
  return Cards;
};