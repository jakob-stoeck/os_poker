INSERT IGNORE INTO `poker_category` VALUES
(1, 'Drinks'),
(2, 'Snacks / Food'),
(3, 'Entertainment'),
(4, 'Smokes');

INSERT IGNORE INTO `poker_item` (`id_category`, `id_item`, `name`, `price`,  `ttl`, `picture`) VALUES
(1, 1, 'Water',4, '+5 minutes', 'sites/default/files/poker_items/item-1.jpg'),
(1, 2, 'Coffee',6, '+10 minutes', 'sites/default/files/poker_items/item-2.jpg'),
(1, 3, 'Beer',6, '+10 minutes', 'sites/default/files/poker_items/item-3.jpg'),
(1, 4, 'Hot milk with honey',8, '+15 minutes', 'sites/default/files/poker_items/item-4.jpg'),
(1, 5, 'Coffee to-go',8, '+10 minutes', 'sites/default/files/poker_items/item-5.jpg'),
(1, 6, 'Energy Drink',10, '+10 minutes', 'sites/default/files/poker_items/item-6.jpg'),
(1, 7, 'Cod liver oil',18, '+15 minutes', 'sites/default/files/poker_items/item-7.jpg'),
(1, 8, 'Tequila',20, '+5 minutes', 'sites/default/files/poker_items/item-8.jpg'),
(1, 9, 'Margarita',30, '+20 minutes', 'sites/default/files/poker_items/item-9.jpg'),
(1, 10, 'Poison',30, '+30 minutes', 'sites/default/files/poker_items/item-10.jpg'),
(1, 11, 'Pina Colada',40, '+25 minutes', 'sites/default/files/poker_items/item-11.jpg'),
(1, 12, 'Cosmopolitan',50, '+20 minutes', 'sites/default/files/poker_items/item-12.jpg'),
(1, 13, 'Mojito',60, '+20 minutes', 'sites/default/files/poker_items/item-13.jpg'),
(1, 14, 'Whiskey',150, '+20 minutes', 'sites/default/files/poker_items/item-14.jpg'),
(1, 15, 'Cognac',200, '+25 minutes', 'sites/default/files/poker_items/item-15.jpg'),
(1, 16, 'Bordeaux Wine',300, '+25 minutes', 'sites/default/files/poker_items/item-16.jpg'),
(1, 17, 'Champagne',600, '+30 minutes', 'sites/default/files/poker_items/item-17.jpg'),
(1, 18, 'Love Potion',1000, '+30 minutes', 'sites/default/files/poker_items/item-18.jpg'),
(1, 19, 'Mezz "Sapphire Martini"',3000, '+60 minutes', 'sites/default/files/poker_items/item-19.jpg'),
(1, 20, 'Algonquin "Martini on the Rock"',10000, '+90 minutes', 'sites/default/files/poker_items/item-20.jpg'),
(2, 21, 'Bowl of rice',5, '+5 minutes', 'sites/default/files/poker_items/item-21.jpg'),
(2, 22, 'lollipop',6, '+10 minutes', 'sites/default/files/poker_items/item-22.jpg'),
(2, 23, 'Peanuts',8, '+10 minutes', 'sites/default/files/poker_items/item-23.jpg'),
(2, 24, 'Lemon',10, '+10 minutes', 'sites/default/files/poker_items/item-24.jpg'),
(2, 25, 'Donut',12, '+10 minutes', 'sites/default/files/poker_items/item-25.jpg'),
(2, 26, 'Can of spinach',15, '+15 minutes', 'sites/default/files/poker_items/item-26.jpg'),
(2, 27, 'Weenie',18, '+15 minutes', 'sites/default/files/poker_items/item-27.jpg'),
(2, 28, 'Pretzel',20, '+10 minutes', 'sites/default/files/poker_items/item-28.jpg'),
(2, 29, 'Burger',30, '+10 minutes', 'sites/default/files/poker_items/item-29.jpg'),
(2, 30, 'Pie',30, '+15 minutes', 'sites/default/files/poker_items/item-30.jpg'),
(2, 31, 'Pizza',40, '+15 minutes', 'sites/default/files/poker_items/item-31.jpg'),
(2, 32, 'Sundae',60, '+20 minutes', 'sites/default/files/poker_items/item-32.jpg'),
(2, 33, 'Chicken Wings',60, '+20 minutes', 'sites/default/files/poker_items/item-33.jpg'),
(2, 34, 'Fish \'n\' Chips',80, '+20 minutes', 'sites/default/files/poker_items/item-34.jpg'),
(2, 35, 'Candies',100, '+30 minutes', 'sites/default/files/poker_items/item-35.jpg'),
(2, 36, 'Texas Chili',120, '+25 minutes', 'sites/default/files/poker_items/item-36.jpg'),
(2, 37, 'Sushi',150, '+30 minutes', 'sites/default/files/poker_items/item-37.jpg'),
(2, 38, 'Candlelight-Dinner',350, '+60 minutes', 'sites/default/files/poker_items/item-38.jpg'),
(2, 39, 'Lobster',650, '+45 minutes', 'sites/default/files/poker_items/item-39.jpg'),
(2, 40, 'Beluga-Caviar',8000, '+45 minutes', 'sites/default/files/poker_items/item-40.jpg'),
(3, 41, 'Tissues',25, '+10 minutes', 'sites/default/files/poker_items/item-41.jpg'),
(3, 42, 'Fish',25, '+10 minutes', 'sites/default/files/poker_items/item-42.jpg'),
(3, 43, 'Fortune Cookie',60, '+5 minutes', 'sites/default/files/poker_items/item-43.jpg'),
(3, 44, 'Rubber Ducky',80, '+15 minutes', 'sites/default/files/poker_items/item-44.jpg'),
(3, 45, 'Piggybank',100, '+15 minutes', 'sites/default/files/poker_items/item-45.jpg'),
(3, 46, '"Congratulation" Ballon',200, '+10 minutes', 'sites/default/files/poker_items/item-46.jpg'),
(3, 47, 'Lawn Gnome',400, '+10 minutes', 'sites/default/files/poker_items/item-47.jpg'),
(3, 48, 'Donkey',600, '+15 minutes', 'sites/default/files/poker_items/item-48.jpg'),
(3, 49, 'Feather Boa (pink)',800, '+15 minutes', 'sites/default/files/poker_items/item-49.jpg'),
(3, 50, 'GoGo Girl',1000, '+20 minutes', 'sites/default/files/poker_items/item-50.jpg'),
(3, 51, 'Rose',1200, '+20 minutes', 'sites/default/files/poker_items/item-51.jpg'),
(3, 52, 'Chill Pill',1500, '+15 minutes', 'sites/default/files/poker_items/item-52.jpg'),
(3, 53, 'Four-leaf clover',2000, '+20 minutes', 'sites/default/files/poker_items/item-53.jpg'),
(3, 54, 'Psychiatrist',2500, '+30 minutes', 'sites/default/files/poker_items/item-54.jpg'),
(3, 55, 'Bling Bling $',3000, '+20 minutes', 'sites/default/files/poker_items/item-55.jpg'),
(3, 56, 'Kiss',4000, '+15 minutes', 'sites/default/files/poker_items/item-56.jpg'),
(3, 57, 'Massage',5000, '+30 minutes', 'sites/default/files/poker_items/item-57.jpg'),
(3, 58, 'Diamond ring',10000, '+60 minutes', 'sites/default/files/poker_items/item-58.jpg'),
(3, 59, 'Rolex',15000, '+60 minutes', 'sites/default/files/poker_items/item-59.jpg'),
(3, 60, 'Yacht',500000, '+90 minutes', 'sites/default/files/poker_items/item-60.jpg'),
(4, 61, 'Cigarettes',10, '+5 minutes', 'sites/default/files/poker_items/item-61.jpg'),
(4, 62, 'Nicotin gum',20, '+10 minutes', 'sites/default/files/poker_items/item-62.jpg'),
(4, 63, 'Brasilia Zopf (cheap cigar)',25, '+15 minutes', 'sites/default/files/poker_items/item-63.jpg'),
(4, 64, 'Incense Sticks',45, '+15 minutes', 'sites/default/files/poker_items/item-64.jpg'),
(4, 65, 'Herbal rolls',65, '+10 minutes', 'sites/default/files/poker_items/item-65.jpg'),
(4, 66, 'Cuban cigar',200, '+20 minutes', 'sites/default/files/poker_items/item-66.jpg'),
(4, 67, 'Wacky terbacky',350, '+20 minutes', 'sites/default/files/poker_items/item-67.jpg'),
(4, 68, 'Peace Pipe',500, '+20 minutes', 'sites/default/files/poker_items/item-68.jpg'),
(4, 69, 'Shisha',2000, '+45 minutes', 'sites/default/files/poker_items/item-69.jpg'),
(4, 70, 'Esplendidos de Cohiba',8000, '+60 minutes', 'sites/default/files/poker_items/item-70.jpg');
      