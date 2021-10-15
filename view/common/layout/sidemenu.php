<style>
    .side-menu {z-index:30;position:fixed; top:10%; left:0; background-color:#1b2b41; border-radius:0 6px 6px 0;top: calc(50% - 160px);}
    .side-menu .side-menu-item {position:relative; display:flex; flex-direction:column; align-items:center; justify-content:center; width:80px; height:80px; padding:10px 0; margin:0 10px; color:#fff; border-bottom:1px solid #fff; cursor:pointer;}
    .side-menu .side-menu-item:last-child {border-bottom:none;}
    .side-menu .side-menu-item i {font-size:1.5rem; font-weight:300;}
    .side-menu .side-menu-item p {margin-top:5px; font-size:.9rem;}
    .side-menu .side-menu-item:hover .side-menu-sub {display:block; position:absolute; left:112%; top:0; width:250px; padding:20px; background-color:#fff; border:1px solid #ddd;}
    .side-menu .side-menu-item .side-menu-sub {display:none;}
    .side-menu .side-menu-item .side-menu-sub b {color:#444; font-size:1.3rem; font-weight:700;}
    .side-menu .side-menu-item .side-menu-sub b i {color:#225aa8;}
    .side-menu .side-menu-mail .side-menu-sub b {font-size:0.85rem;}
    .side-menu .side-menu-item .side-menu-sub span {display:block; color:#444; font-size:.9rem;}
    .side-menu .side-menu-item a {display:flex; flex-direction:column; align-items:center; justify-content:center;}

    .mo_side-menu {display:none;}
    @media screen and (max-width: 768px){
        .pc_side-menu {display:none;}

        .mo_side-menu {z-index:100; display:block; position:fixed; bottom:0; left:0; right:0; background-color:#fff; box-shadow:1px 0 14px rgba(0,0,0,0.2);}
        .mo_menu-cont {display:flex; align-items:center; justify-content:space-around;}
        .mo_menu-cont a {display:flex; flex-direction:column; align-items:center; justify-content:space-around; font-size:.8rem; color:#444; width:25%; padding:15px 0;}
        .mo_menu-cont a i {font-size:1.4rem; color:#444;}
    }
</style>