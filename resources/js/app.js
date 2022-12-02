import './bootstrap';
import '../sass/app.scss';

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import * as resumablejs from "resumablejs";
window.Resumable = resumablejs;
