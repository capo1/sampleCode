import { setSchemaButtonAction } from "./inc/modal";


(function ($) {
	if ($("#schemaModal").length > 0) {
		
		const linkImg = $('#parking-link').find('img');
	
		setSchemaButtonAction({
			modalId: "schemaModal",
			schemaTitle: linkImg.attr('alt'),
			imageUrl: linkImg.attr('src'),
			imageAlt: "",
			buttonClassTrigerClick: "parking-link",
			loadImgElId: "modal-img",
		});
	}
  
});