document.addEventListener("DOMContentLoaded", (event) => {
    const flashes=document.querySelectorAll('li.toast');
    flashes.forEach((flashItem)=>{
        flashItem.timeOutId=setTimeout(()=>removeToast(flashItem),5000)
    })
  });

const removeToast=(toast)=>{
    toast.classList.add('toast_hide');
    if(toast.timeOutId)
        clearTimeout(toast.timeOutId);
    setTimeout(()=>toast.remove(),500);
}

