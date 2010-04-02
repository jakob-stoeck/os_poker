from windmill.authoring import WindmillTestClient

def login(client, user, password):
    client.waits.forElement(classname=u'poker_submit')
    client.type(text=user, id=u'edit-name')
    client.type(text=password, id=u'edit-pass')
    client.click(classname=u'poker_submit')
    client.waits.forElement(classname=u'jpoker_table_list_table_empty')

def test_login():
    client = WindmillTestClient(__name__)
    login(client, 'root', 'root')
