require('./bootstrap')
require('./aragon')
require('vue-moment')

import {TweenMax, Power2, TimelineLite} from "gsap/TweenMax"

import * as Alerts from './utils/alerts.js'
import * as Constants from './utils/constans.js'
import FileManager from './utils/file_manager.js'
import FileProvider from './providers/file_provider.js'
import './utils/validators.js'

window.Alerts = Alerts
window.Constants = Constants
window.FileManager = FileManager
window.FileProvider = FileProvider