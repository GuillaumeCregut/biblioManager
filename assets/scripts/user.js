const avatarImg=document.getElementById('avatar-img');
const avatarFile=document.getElementById('user_avatarUpload');


avatarFile.addEventListener('change',function(e){
    if(e.target.files[0].size<500000){
        avatarImg.src=URL.createObjectURL(e.target.files[0]);
    } else {
        alert('Le fichier est trop gros');
        e.target.files[0]=null;
        avatarFile.value='';
    }
});