export default class FlashMessageHandler {
    constructor(elements) {
        $(elements).each(function(index, element) {
            $(this).fadeIn(200 + (index * 500));
            setTimeout( () => {
                $(this).fadeOut(() => {
                    $(elements).animate({"top": "-=55px"});
                });
            }, 3000 + index*1500)
        })
    }
}