<?php if (!defined('THINK_PATH')) exit(); $config = D("Basic")->select(); $navs = D("Menu")->getNormalBarMenus(); ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo ($config["title"]); ?></title>
  <meta name="keywords" content="<?php echo ($config["keywords"]); ?>" />
  <meta name="description" content="<?php echo ($config["description"]); ?>" />
  <link rel="stylesheet" href="/Public/css/bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="/Public/css/home/main.css" type="text/css" />
</head>
<body>
<header id="header">
  <div class="navbar-inverse">
    <div class="container">
      <div class="navbar-header">
        <a href="/">
          <img src="/Public/images/logo.png" alt="">
        </a>
      </div>
      <ul class="nav navbar-nav navbar-left">
        <li><a href="/" <?php if($result['catid'] == 0): ?>class="curr"<?php endif; ?>>首页</a></li>
        <?php if(is_array($navs)): foreach($navs as $key=>$nav): ?><li><a href="/index.php?c=cat&id=<?php echo ($nav["menu_id"]); ?>" <?php if($nav['menu_id'] == $result['catid']): ?>class="curr"<?php endif; ?>><?php echo ($nav["name"]); ?></a></li><?php endforeach; endif; ?>
      </ul>
    </div>
  </div>
</header>
<section>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-9">
        <div class="banner">
          <div class="banner-left">
            <a target="_blank" href="/index.php?c=detail&id=<?php echo ($result['topPicNews'][0]['news_id']); ?>"  ><img width="670" height="480" src="<?php echo ($result['topPicNews'][0]['thumb']); ?>" alt=""></a>
          </div>
          <div class="banner-right">
            <ul>
              <?php if(is_array($result['topSmallNews'])): $i = 0; $__LIST__ = $result['topSmallNews'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$topSN): $mod = ($i % 2 );++$i;?><li>
                <a target="_blank" href="/index.php?c=detail&id=<?php echo ($topSN["news_id"]); ?>"><img width="150" height="150" src="<?php echo ($topSN["thumb"]); ?>" alt="<?php echo ($topSN["title"]); ?>"></a>
              </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
          </div>
        </div>
        <div class="news-list">
          <?php if(is_array($result['listNews'])): $i = 0; $__LIST__ = $result['listNews'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$listNews): $mod = ($i % 2 );++$i;?><dl>
              <a target="_blank" href="/index.php?c=detail&id=<?php echo ($listNews["news_id"]); ?>"><dt><?php echo ($listNews["title"]); ?></dt></a>
            <dd class="news-img">
              <a target="_blank" href="/index.php?c=detail&id=<?php echo ($listNews["news_id"]); ?>"><img width="200" height="120" src="<?php echo ($listNews["thumb"]); ?>" alt="<?php echo ($listNews["title"]); ?>"></a>
            </dd>
            <dd class="news-intro">
              <?php echo ($listNews["description"]); ?>
            </dd>
            <dd class="news-info">
              <?php echo ($listNews["keywords"]); ?> <span><?php echo (date("Y-m-d H:i:s",$listNews["create_time"])); ?></span> 阅读(<i news-id="<?php echo ($listNews["news_id"]); ?>" class="news_count node-<?php echo ($listNews["news_id"]); ?>"><?php echo ($listNews["count"]); ?></i>)
            </dd>
          </dl><?php endforeach; endif; else: echo "" ;endif; ?>   
        </div>
      </div>
      <!--网站右侧排行-->
      <div class="col-sm-3 col-md-3">
        <div class="right-title">
          <h3>文章排行</h3>
          <span>TOP ARTICLES</span>
        </div>
        <div class="right-content">
          <ul>
            <?php if(is_array($result['rankNews'])): $i = 0; $__LIST__ = $result['rankNews'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rankNews): $mod = ($i % 2 );++$i;?><li <?php if($i == 1): ?>class="num<?php echo ($i); ?> curr"<?php else: ?>class="num<?php echo ($i); ?>"<?php endif; ?>>
              <a target="_blank" href="/index.php?c=detail&id=<?php echo ($rankNews["news_id"]); ?>"><?php echo ($rankNews["small_title"]); ?></a>
              <?php if($i == 1): ?><div class="intro">
                <?php echo ($rankNews["description"]); ?>
              </div><?php endif; ?>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>

          </ul>
        </div>
        
        <?php if(is_array($result['advNews'])): $i = 0; $__LIST__ = $result['advNews'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$advNews): $mod = ($i % 2 );++$i;?><div class="right-hot">
          <a target="_blank" href="<?php echo ($advNews["url"]); ?>"><img src="<?php echo ($advNews["thumb"]); ?>" alt="<?php echo ($advNews["name"]); ?>"></a>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
    </div>
  </div>
</section>
</body>
<script src="/Public/js/jquery.js"></script>
<script src="/Public/js/count.js"></script>
</html>