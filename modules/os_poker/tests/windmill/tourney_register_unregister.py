from windmill.authoring import WindmillTestClient
from signup import signup

def test_os_poker_tourney_register_unregister():
    client = WindmillTestClient(__name__)

    signup(client)
    client.click(xpath=u"//li[@id='lobby_sng']/a")
    client.waits.forElement(classname=u'jpoker_tourney_state_registering')
    client.click(classname=u'jpoker_tourney_state_registering')
    client.waits.sleep(milliseconds=u'1000')
    client.click(id=u"lobby_join_table")
    for i in range(100):
        print i
        client.waits.forElement(value=u'Register')
        client.click(value=u'Register')
        client.waits.forElement(value=u'Unregister')
        client.click(value=u'Unregister')
