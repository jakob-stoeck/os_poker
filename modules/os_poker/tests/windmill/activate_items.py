from windmill.authoring import WindmillTestClient

def login(client, user, password):
  client.waits.forElement(classname=u'poker_submit')
  client.type(text=user, id=u'edit-name')
  client.type(text=password, id=u'edit-pass')
  client.click(classname=u'poker_submit')
  client.waits.forElement(classname=u'jpoker_table_list_table_empty')

def activate_items():
  client = WindmillTestClient(__name__)
  #Names of items to buy and activate
  itemNames = ['Water', 'Coffee', 'Beer', 'Hot milk', 'Coffee to-go']
  login(client, 'root', 'root')
  #Store base URL
  client.storeVarFromJS(options=u"BaseUrl|window.location.href.replace(window.location.search, '')")
  #Go to Shop
  client.storeURL(link=u'Shop')
  client.open(url=u'{$Shop}')
  client.waits.forElement(id=u'items')
  #Buy items
  for itemName in itemNames:
    client.click(jquery=(u"('a[title=%s]')[0]" % itemName))
    client.click(jquery=u"('.poker_submit[onclick*=subtarget]')[0]")
  #Go to medium profile to extract item IDs
  client.open(url=u'{$BaseUrl}?q=poker/profile/medium')
  for itemName in itemNames:
    client.storeVarFromJS(options=(u'%sId|$("img[title=%s]").attr("id")' % (itemName, itemName)))
  #Go and sit to a table
  client.open(url=u'{$BaseUrl}')
  client.waits.forElement(classname=u'jpoker_table_list_table_empty')
  client.click(id=u'play-now-button')
  client.waits.forElement(classname=u'jpoker_table',timeout=u'20000')
  #Items activation using JS (couldn't get Windmill to select and click in the iframe)
  for itemName in itemNames:
    #Set item_id JS variable using a JS assertion because Windmill variables are not working in execJS
    client.asserts.assertJS(js=u'!isNaN(item_id = parseInt("{$%sId}"))' % itemName)
    client.execJS(js=u'os_poker_send_message({type:"os_poker_activate_item", id_item: item_id});')
    #Wait for the item to be received by the player
    client.waits.forElement(classname=u'poker_player_item poker_gift_%s' % itemName.replace(' ', '-'),timeout=u'7000')
  client.click(classname=u'logout')
