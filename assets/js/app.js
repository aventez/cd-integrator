import '../scss/style.scss';
import '@coreui/coreui';

import FlashMessageHandler from "./Component/FlashMessageHandler";

$(function () {
   new FlashMessageHandler($(".alert-fixed"));
});