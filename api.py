import requests
from telegram.update import Update
import json

def request(update, context):
    """
    input: telegram objects
    output: json
    return all gas stations (with their characteristics) of the department for a given fuel
    """
    base_url = "http://s904857832.onlinehome.fr/5c34a5f29c1b6cd7988de67a147b601a.php/"
    api_key = "a692a8fc4c7260188bbb5422f53a34af"
    cp = context.args[0]
    ca = context.args[1]
    cp_r = cp[:2]
    request_carbu_url = base_url + "?key=" + api_key + "&cp=" + cp_r + "&ca=" + ca
    return json.loads(requests.get(request_carbu_url).text), cp, ca