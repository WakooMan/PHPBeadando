const form1 = document.querySelector('#lapozasform1');
const form2 = document.querySelector('#lapozasform2');
const tabla1 = document.querySelector('#tabla1');
const tabla2 = document.querySelector('#tabla2');
if(form1!=null)
{
    for(let child of form1.children)
    {
        child.addEventListener('click',form1Event);
    }
}
console.log(form2);
for(let child of form2.children)
{
    child.addEventListener('click',form2Event);
    console.log(child);
}
async function form1Event(e)
{
    let values = e.target.value.split('-');
    let tol = values[0];
    let ig = values[1];
    let resp = await fetch('ajax.php?sorozatok=elkezdett&nev=visitedserieslap&tol='+tol+'&ig='+ig);
    let data = await resp.text();
    console.log(data);
    let array = data.split('|');
    tabla1.innerHTML = array[0];
    form1.innerHTML=array[1];
    for(let child of form1.children)
    {
        child.addEventListener('click',form1Event);
    }
}

async function form2Event(e)
{
    let values = e.target.value.split("-");
    let tol = values[0];
    let ig = values[1];
    let resp = await fetch('ajax.php?sorozatok=osszes&nev=allserieslap&tol='+tol+'&ig='+ig);
    let data = await resp.text();
    let array = data.split('|');
    tabla2.innerHTML = array[0];
    form2.innerHTML=array[1];
    for(let child of form2.children)
    {
        child.addEventListener('click',form2Event);
    }
}