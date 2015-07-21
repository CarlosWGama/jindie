<div id="<?=$menuID?>" class="<?=$menuClass?>">
  <style scoped>
  @import url(http://fonts.googleapis.com/css?family=Open+Sans);
  #<?=$menuID?>,
  #<?=$menuID?> ul,
  #<?=$menuID?> ul li,
  #<?=$menuID?> ul li a,
  #<?=$menuID?> #menu-button {
    margin: 0;
    padding: 0;
    border: 0;
    list-style: none;
    line-height: 1;
    display: block;
    position: relative;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
  }
  #<?=$menuID?>:after,
  #<?=$menuID?> > ul:after {
    content: ".";
    display: block;
    clear: both;
    visibility: hidden;
    line-height: 0;
    height: 0;
  }
  #<?=$menuID?> #menu-button {
    display: none;
  }
  #<?=$menuID?> {
    width: auto;
    font-family: 'Open Sans', sans-serif;
    line-height: 1;
    background: #ffffff;
  }
  #menu-line {
    position: absolute;
    top: 0;
    left: 0;
    height: 3px;
    background: #009ae1;
    -webkit-transition: all 0.25s ease-out;
    -moz-transition: all 0.25s ease-out;
    -ms-transition: all 0.25s ease-out;
    -o-transition: all 0.25s ease-out;
    transition: all 0.25s ease-out;
  }
  #<?=$menuID?> > ul > li {
    float: left;
  }
  #<?=$menuID?>.align-center > ul {
    font-size: 0;
    text-align: center;
  }
  #<?=$menuID?>.align-center > ul > li {
    display: inline-block;
    float: none;
  }
  #<?=$menuID?>.align-center ul ul {
    text-align: left;
  }
  #<?=$menuID?>.align-right > ul > li {
    float: right;
  }
  #<?=$menuID?>.align-right ul ul {
    text-align: right;
  }
  #<?=$menuID?> > ul > li > a {
    padding: 20px;
    font-size: 12px;
    text-decoration: none;
    text-transform: uppercase;
    color: #000000;
    -webkit-transition: color .2s ease;
    -moz-transition: color .2s ease;
    -ms-transition: color .2s ease;
    -o-transition: color .2s ease;
    transition: color .2s ease;
  }
  #<?=$menuID?> > ul > li:hover > a,
  #<?=$menuID?> > ul > li.active > a {
    color: #009ae1;
  }
  #<?=$menuID?> > ul > li.has-sub > a {
    padding-right: 25px;
  }
  #<?=$menuID?> > ul > li.has-sub > a::after {
    position: absolute;
    top: 21px;
    right: 10px;
    width: 4px;
    height: 4px;
    border-bottom: 1px solid #000000;
    border-right: 1px solid #000000;
    content: "";
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    -o-transform: rotate(45deg);
    transform: rotate(45deg);
    -webkit-transition: border-color 0.2s ease;
    -moz-transition: border-color 0.2s ease;
    -ms-transition: border-color 0.2s ease;
    -o-transition: border-color 0.2s ease;
    transition: border-color 0.2s ease;
  }
  #<?=$menuID?> > ul > li.has-sub:hover > a::after {
    border-color: #009ae1;
  }
  #<?=$menuID?> ul ul {
    position: absolute;
    left: -9999px;
  }
  #<?=$menuID?> li:hover > ul {
    left: auto;
  }
  #<?=$menuID?>.align-right li:hover > ul {
    right: 0;
  }
  #<?=$menuID?> ul ul ul {
    margin-left: 100%;
    top: 0;
  }
  #<?=$menuID?>.align-right ul ul ul {
    margin-left: 0;
    margin-right: 100%;
  }
  #<?=$menuID?> ul ul li {
    height: 0;
    -webkit-transition: height .2s ease;
    -moz-transition: height .2s ease;
    -ms-transition: height .2s ease;
    -o-transition: height .2s ease;
    transition: height .2s ease;
  }
  #<?=$menuID?> ul li:hover > ul > li {
    height: 32px;
  }
  #<?=$menuID?> ul ul li a {
    padding: 10px 20px;
    width: 160px;
    font-size: 12px;
    background: #333333;
    text-decoration: none;
    color: #dddddd;
    -webkit-transition: color .2s ease;
    -moz-transition: color .2s ease;
    -ms-transition: color .2s ease;
    -o-transition: color .2s ease;
    transition: color .2s ease;
  }
  #<?=$menuID?> ul ul li:hover > a,
  #<?=$menuID?> ul ul li a:hover {
    color: #ffffff;
  }
  #<?=$menuID?> ul ul li.has-sub > a::after {
    position: absolute;
    top: 13px;
    right: 10px;
    width: 4px;
    height: 4px;
    border-bottom: 1px solid #dddddd;
    border-right: 1px solid #dddddd;
    content: "";
    -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    -o-transform: rotate(-45deg);
    transform: rotate(-45deg);
    -webkit-transition: border-color 0.2s ease;
    -moz-transition: border-color 0.2s ease;
    -ms-transition: border-color 0.2s ease;
    -o-transition: border-color 0.2s ease;
    transition: border-color 0.2s ease;
  }
  #<?=$menuID?>.align-right ul ul li.has-sub > a::after {
    right: auto;
    left: 10px;
    border-bottom: 0;
    border-right: 0;
    border-top: 1px solid #dddddd;
    border-left: 1px solid #dddddd;
  }
  #<?=$menuID?> ul ul li.has-sub:hover > a::after {
    border-color: #ffffff;
  }
  @media all and (max-width: 768px), only screen and (-webkit-min-device-pixel-ratio: 2) and (max-width: 1024px), only screen and (min--moz-device-pixel-ratio: 2) and (max-width: 1024px), only screen and (-o-min-device-pixel-ratio: 2/1) and (max-width: 1024px), only screen and (min-device-pixel-ratio: 2) and (max-width: 1024px), only screen and (min-resolution: 192dpi) and (max-width: 1024px), only screen and (min-resolution: 2dppx) and (max-width: 1024px) {
    #<?=$menuID?> {
      width: 100%;
    }
    #<?=$menuID?> ul {
      width: 100%;
      display: none;
    }
    #<?=$menuID?>.align-center > ul,
    #<?=$menuID?>.align-right ul ul {
      text-align: left;
    }
    #<?=$menuID?> ul li,
    #<?=$menuID?> ul ul li,
    #<?=$menuID?> ul li:hover > ul > li {
      width: 100%;
      height: auto;
      border-top: 1px solid rgba(120, 120, 120, 0.15);
    }
    #<?=$menuID?> ul li a,
    #<?=$menuID?> ul ul li a {
      width: 100%;
    }
    #<?=$menuID?> > ul > li,
    #<?=$menuID?>.align-center > ul > li,
    #<?=$menuID?>.align-right > ul > li {
      float: none;
      display: block;
    }
    #<?=$menuID?> ul ul li a {
      padding: 20px 20px 20px 30px;
      font-size: 12px;
      color: #000000;
      background: none;
    }
    #<?=$menuID?> ul ul li:hover > a,
    #<?=$menuID?> ul ul li a:hover {
      color: #000000;
    }
    #<?=$menuID?> ul ul ul li a {
      padding-left: 40px;
    }
    #<?=$menuID?> ul ul,
    #<?=$menuID?> ul ul ul {
      position: relative;
      left: 0;
      right: auto;
      width: 100%;
      margin: 0;
    }
    #<?=$menuID?> > ul > li.has-sub > a::after,
    #<?=$menuID?> ul ul li.has-sub > a::after {
      display: none;
    }
    #menu-line {
      display: none;
    }
    #<?=$menuID?> #menu-button {
      display: block;
      padding: 20px;
      color: #000000;
      cursor: pointer;
      font-size: 12px;
      text-transform: uppercase;
    }
    #<?=$menuID?> #menu-button::after {
      content: '';
      position: absolute;
      top: 20px;
      right: 20px;
      display: block;
      width: 15px;
      height: 2px;
      background: #000000;
    }
    #<?=$menuID?> #menu-button::before {
      content: '';
      position: absolute;
      top: 25px;
      right: 20px;
      display: block;
      width: 15px;
      height: 3px;
      border-top: 2px solid #000000;
      border-bottom: 2px solid #000000;
    }
    #<?=$menuID?> .submenu-button {
      position: absolute;
      z-index: 10;
      right: 0;
      top: 0;
      display: block;
      border-left: 1px solid rgba(120, 120, 120, 0.15);
      height: 52px;
      width: 52px;
      cursor: pointer;
    }
    #<?=$menuID?> .submenu-button::after {
      content: '';
      position: absolute;
      top: 21px;
      left: 26px;
      display: block;
      width: 1px;
      height: 11px;
      background: #000000;
      z-index: 99;
    }
    #<?=$menuID?> .submenu-button::before {
      content: '';
      position: absolute;
      left: 21px;
      top: 26px;
      display: block;
      width: 11px;
      height: 1px;
      background: #000000;
      z-index: 99;
    }
    #<?=$menuID?> .submenu-button.submenu-opened:after {
      display: none;
    }
  }
  </style>

  <?=$menu?>


</div>