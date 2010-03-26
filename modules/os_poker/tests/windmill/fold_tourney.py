from windmill.authoring import WindmillTestClient
from login import login
import functest

def test_os_poker_fold():
    client = WindmillTestClient(__name__)
    username = functest.registry.get('username')
    password = functest.registry.get('password', username)
    login(client, username, password)
    client.click(classname=u'close')
    client.waits.forElement(jquery=u'(".notify-text a")[0]')
    client.click(jquery=u'(".notify-text a")[0]')
    client.waits.forElement(classname=u'jpoker_table')
    for i in range(100):
        client.click(classname=u'jpoker_ptable_fold')
        client.waits.sleep(milliseconds=5000)
