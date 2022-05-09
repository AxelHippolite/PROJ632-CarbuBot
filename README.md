![](assets/title.jpg)
## Introduction
The goal of this project was to propose a bot on Telegram able to tell a user which are the 5 cheapest gas stations in a 10 KM radius from his position.

## How Does It Works ?
A PHP code updates twice a day a database with the new prices of all the gas stations in France (based on an XML file updated every 10 minutes and provided by the government). Another PHP code manages the requests sent by a Python code that takes care of interacting with the user through the Telegram application.

In order to use the code correctly, we invite you to follow the few steps below :
1. Run the program.
2. Download Telegram.
3. Run Telegram.
4. Add @CarbuBot on Telegram.
5. Follow GIF instructions.

The GIF below illustrates the different steps detailed above :

![](assets/carbubot.gif)
## Version
Made with Python 3.10.0 & python-telegram-bot & PHP
