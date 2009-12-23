/*
 * Provides a fake Drupal object to allow scripts relaying on it to be loaded
 */
Drupal = {
  behaviors: {},
  theme: function(func) {
    for (var i = 1, args = []; i < arguments.length; i++) {
      args.push(arguments[i]);
    }

    return (Drupal.theme[func] || Drupal.theme.prototype[func]).apply(this, args);
  }
};