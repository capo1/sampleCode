//New modal Class with callback on Close

class modal {
  constructor(el) {
    this.params = {
      modal: el, //modal window
      closeBtn: el.getElementsByClassName("close")[0], //closeBtn
      titleField: el.getElementsByClassName("title")[0], //modal title
      fn: () => { }, //calback on close
    };

    this.setActions();
  }
  show(fn) {
    if (!fn || typeof fn !== "function") {
      fn = false;
    }

    this.params.fn = fn;
    this.params.modal.classList.toggle("visible");
  }
  close() {
    this.params.modal.classList.remove("visible");

    if (typeof this.params.fn !== "function") {
      this.params.fn = false;
    }

    if (this.params.fn) {
      this.params.fn();
    }
  }
  title(txt) {
    this.params.titleField.innerHTML = txt;
  }
  setActions() {
    let th = this; //I hate this :)

    this.params.closeBtn.addEventListener("click", () => {
      th.close();
    });

    this.params.modal.addEventListener("click", (e) => {
      if (e.target == th.params.modal) {
        th.close();
      }
    });
  }
}

export function setSchemaButtonAction(params) {
  /*
    params:{
        modalId - get element by this id for modal,
        schemaTitle - title schemat for modal
        buttonClassTrigerClick - elements by class to trigger modal and load image
        loadImgElId - load image to this element in modal content
    }
    */

  const img = document.getElementById(params.loadImgElId),
        btns = document.getElementsByClassName(params.buttonClassTrigerClick),
        schemaModal = new modal(document.getElementById(params.modalId));

  const loadImage = function (src) {
    let downloadingImage = new Image();
    downloadingImage.onload = function () {
      img.src = src;
    };
    downloadingImage.src = src;
  };

  for (let item of btns) {
    item.onclick = function () {
      loadImage(params.imageUrl);
      schemaModal.title(params.schemaTitle + " " + params.imageAlt);
      schemaModal.show();
    };
  }
}