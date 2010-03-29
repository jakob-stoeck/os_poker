
from windmill.authoring import WindmillTestClient
from login import login
import functest

def test_register():
    client = WindmillTestClient(__name__)
    username = functest.registry.get('username')
    password = functest.registry.get('password', username)
    login(client, username, password)
    client.click(xpath=u"//li[@id='lobby_regular']/a")
    client.waits.forElement(classname=u'jpoker_tourney_state_registering')
    client.click(classname=u'jpoker_tourney_state_registering')
    client.waits.sleep(milliseconds=u'1000')
    client.click(id=u"lobby_join_table")
    client.waits.forElement(value=u'Register')
    client.click(value=u'Register')
