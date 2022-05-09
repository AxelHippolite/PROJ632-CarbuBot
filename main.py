import requests
import json
import geo
import api
from telegram.ext.updater import Updater
from telegram.update import Update
from telegram.ext.callbackcontext import CallbackContext
from telegram.ext.commandhandler import CommandHandler
from telegram.ext.messagehandler import MessageHandler
from telegram.ext.filters import Filters

def start(update, context):
    """
    bot initialization 
    """
    update.message.reply_text("Bienvenue sur CarbuBot !\nIci, il suffit de rentrer votre Code Postal et CarbuBot s'occupe de vous trouver les 5 Stations d'Essence les moins chère autour de vous !\nVous trouverez plus d'informations en utilisant la commande /help.")
    
def find(update, context):
    """
    input: telegram objects
    output: none
    display the 5 cheapest gas stations within a 10km radius
    """
    resp_carbu, cp, ca = api.request(update, context) #get all gas stations (with their characteristics) of the department for a given fuel
    resp_geo = json.loads(requests.get('https://geo.api.gouv.fr/communes?codePostal=' + cp).text) #get the city of the given postal code
    update.message.reply_text("Code Postal : " + cp + "\nCarburant Choisi : " + ca.upper() + "\nRecherche en cours...")
    if len(resp_carbu) != 0:
        limit = 5
        all_station = []
        city = resp_geo[0]['nom'] #get the name of city of the given postal code
        origin_coord = json.loads(requests.get('https://geo.api.gouv.fr/communes?nom=' + city + '&fields=code,nom,centre').text)[0]['centre']['coordinates'] #get the coordinates of city of the given postal code
        origin = (origin_coord[1], origin_coord[0])
        update.message.reply_text("# Recapitulatif :")
        update.message.reply_text("Ville : " + city)
        for i in range(len(resp_carbu)): #get the 5 cheapest gas stations within a 10km radius
            if len(all_station) < limit:
                far = (float(resp_carbu[str(i)]['latitude']), float(resp_carbu[str(i)]['longitude']))
                if resp_carbu[str(i)][ca] != None and geo.haversine(origin, far) <= 10 :
                        all_station.append(resp_carbu[str(i)][ca] + " € | Adresse : " + resp_carbu[str(i)]['adress'] + ", " + resp_carbu[str(i)]['city']) #add a station in the list
    else:
        update.message.reply_text("Aucune Station Disponible.")
    string = ""
    for i in range(len(all_station)): #display all gas stations in the list on telegram
        string += "\n" + str(i+1) + ". " + all_station[i]
    update.message.reply_text(string)

if __name__ == "__main__":
    updater = Updater("5121636211:AAGmncpq3_fZ-khdk0AB0q8EpCUN3DWo128", use_context=True) #bot functionality manager
    updater.dispatcher.add_handler(CommandHandler('start', start))
    updater.dispatcher.add_handler(CommandHandler('find', find, pass_args=True))
    updater.start_polling()
