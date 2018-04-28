'use strict';
module.exports = {
  up: (queryInterface, Sequelize) =>
    queryInterface.createTable('Cards', {
      id: {
        allowNull: false,
        autoIncrement: true,
        primaryKey: true,
        type: Sequelize.INTEGER
      },
      name: {
        type: Sequelize.STRING
      },
      character: {
        type: Sequelize.STRING,
        allowNull: true
      },
      cost: {
        type: Sequelize.STRING
      },
      type: {
        type: Sequelize.STRING
      },
      rarity: {
        type: Sequelize.STRING
      },
      description: {
        type: Sequelize.STRING
      },
      upgrade_description: {
        type: Sequelize.STRING,
        allowNull: true
      },
      upgrade_cost: {
        type: Sequelize.STRING,
        allowNull: true
      },
      createdAt: {
        allowNull: false,
        type: Sequelize.DATE
      },
      updatedAt: {
        allowNull: false,
        type: Sequelize.DATE
      }
    }),
  down: (queryInterface, Sequelize) => queryInterface.dropTable('Cards')
};