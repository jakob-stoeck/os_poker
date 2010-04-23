#!/usr/bin/env sh
#    Copyright (C) 2009, 2010 Pokermania
#    Copyright (C) 2010 OutFlop
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
curl -vvv --cookie /tmp/cookies --cookie-jar /tmp/cookies -d "name=test86@test.com&pass=test86&form-id=user_login_block&form-build-id=form-e9437a6fc1f997c276d1eaf13130c997" http://drupal-dev.pokersource.info/drupal6/?q=node&destination=node
