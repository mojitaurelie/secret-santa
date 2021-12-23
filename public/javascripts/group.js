window.addEventListener('load', () => {
    const myModal = new bootstrap.Modal(document.getElementById('add-participant-modal'));
    document.getElementById('open-modal').addEventListener('click', () => {
        myModal.show();
    });
   document.getElementById('btn-add-user').addEventListener('click', () => {
       const email = document.getElementById('email').value;
       const id = document.getElementById('group-id').value;

       const bodyFormData = new FormData();
       bodyFormData.append('email', email);
       bodyFormData.append('id', id);
       if (email && id) {
           axios({
               method: "post",
               url: "/group/adduser.php",
               data: bodyFormData,
               headers: { "Content-Type": "multipart/form-data" },
           })
           .catch(function (error) {
               console.log(error);
           })
           .then(function () {
               myModal.hide();
               location.reload();
           });
       }
   });
});