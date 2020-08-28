"use strict";

import "leaflet/dist/leaflet.css";
import "../sass/style.scss";

import initView from "./fn/view";

window.app = window.app || {};

$(document).ready(() => {
  initView();
});
