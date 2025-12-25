function openToast(message, bgColor = "#333",color) {
  const toastCon = document.querySelector(".toast_con");
  const toastMessage = document.querySelector(".toast_message");

  toastMessage.innerHTML = message;
  toastMessage.style.color=color;
  toastCon.style.backgroundColor = bgColor;

  // Reset display before showing
  toastCon.style.display = "flex";

  // Force reflow to restart animation
  toastCon.classList.remove("toast_show");
  void toastCon.offsetWidth; // trigger reflow
  toastCon.classList.add("toast_show");

  setTimeout(() => {
    toastCon.classList.remove("toast_show");
    toastCon.style.display = "none";
  }, 2000);
}