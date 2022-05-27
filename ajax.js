const form1 = document.querySelector('#lapozasform1');
const form2 = document.querySelector('#lapozasform2');
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
    form1.innerHTML=data;
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
    form2.innerHTML=data;
    for(let child of form2.children)
    {
        child.addEventListener('click',form2Event);
    }
}