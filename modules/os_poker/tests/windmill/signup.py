from windmill.authoring import WindmillTestClient
from hashlib import md5
from time import time

def signup(client):
    client.click(id=u'edit-mail')
    client.type(text=u'user%s@email.com' % md5(str(time())).hexdigest(), id=u'edit-mail')
    client.type(text=u'password', id=u'edit-pass-1')
    client.waits.forPageLoad(timeout=u'20000')
    client.waits.forElement(xpath=u"//form[@id='os-poker-sign-up-form']/div/div[4]/div[2]", timeout=u'8000')
    client.click(xpath=u"//form[@id='os-poker-sign-up-form']/div/div[4]/div[2]")
    client.waits.forPageLoad(timeout=u'20000')
    client.waits.forElement(xpath=u"//div[@id='poker-first-profile']/a", timeout=u'8000')
    client.click(xpath=u"//div[@id='poker-first-profile']/a")
    client.click(link=u'X')
    client.waits.forElement(classname=u'jpoker_table_list_table_empty')

def test_signup():
    client = WindmillTestClient(__name__)
    signup(client)
