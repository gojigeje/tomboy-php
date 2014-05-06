<?php
  // https://wiki.gnome.org/Apps/Tomboy/UsageIdeas

  $TOMBOY_PATH = "tomboy";
  $GREETING = "tomboy@gojibuntu";

  // GET CLIENT IP 
  function getRealIpAddr()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }
  // DETERMINE LOCAL / NOT?
  function isLocal($a) {
     if ($a=="127.0.0.1") {
        $return = "localhost";
     } elseif (substr($a, 0, 10) === '192.168.2.') {
        $return = "192.168.2.2";
     } else {
        $return = "tomboy.sejak.tk";
     }
     return $return;
  }

  // goji add private & protected
  function finalcek($str) {
    $lokal = isLocal(getRealIpAddr());

    // cek private
    if (strpos($str,'[tomboy-private]') !== false) {
      if ($lokal == "tomboy.sejak.tk") {
        return "<h1>[private note]</h1><br>For localhost's eyes only [o_O]";
      } else {
        return "$str";
      }

    } elseif (strpos($str,'[tomboy-protected]') !== false) {
        return "[protected note]";

    } else {
      return $str;
    }

  }

  // goji fix
  // autolink --> http://code.seebz.net/p/autolink-php/
  function autolink($str, $attributes=array()) {
    $attrs = '';
    foreach ($attributes as $attribute => $value) {
      $attrs .= " {$attribute}=\"{$value}\"";
    }
    $str = ' ' . $str;
    // fix tabs
    $str = str_replace("\t",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$str); 

    // fix 28/12/2013 16:46:22
    // more complete url detection
    // http://stackoverflow.com/questions/10002227
    $pattern = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';
    return preg_replace_callback("#$pattern#i", function($matches) {
        $input = $matches[0];
        $url = preg_match('!^https?://!i', $input) ? $input : "http://$input";
        return '<a href="' . $url . '" rel="nofollow" target="_blank">' . "$input</a>";
    }, $str); 

    $str = substr($str, 1);
    return $str;
  }

  function resolveInternalLinks($notes, $text) {
    $links = array();
    foreach($notes as $note) {
      $link = array();
      $link["string"] = "<link:internal>" . $note["content"]["title"] . "</link:internal>";
      $link["title"] = $note["content"]["title"];
      $link["id"] = $note["id"];
      $links[] = $link;
    }
    foreach($links as $link) {
      $text = str_replace($link["string"], "<a href=\"?note=" . $link["id"] . "\">" . $link["title"] . "</a>&crarr;", $text);
    }
    return $text;
  }

  function findNoteIdByTitle($notes, $title) {
    foreach($notes as $note) {
      if($note["content"]["title"] == $title) {
        return $note["id"];
      }
    }
    return "";
  }

  function getNote($id, $rev, $full) {
    global $TOMBOY_PATH, $notes;
    $ret = array();
    #$path = $TOMBOY_PATH . "/0/" . $rev . "/" . $id . ".note";
    $path = $TOMBOY_PATH . "/" . $id . ".note";
    $xmlnotedoc = new DOMDocument();
    $xmlnotedoc->resolveExternals = false;        
    $xmlnotedoc->load($path);
    $node = $xmlnotedoc->documentElement;
    foreach($node->childNodes as $cn) {
      switch($cn->nodeName) {
        case "title":
          $ret["title"] = utf8_decode($cn->nodeValue); 
          break;
        case "text":
          if($full) {
            $note_content_node = $cn->childNodes->item(0);
            $ret["text"] = utf8_decode($xmlnotedoc->saveXML($note_content_node));
            $ret["text"] = str_replace("\n", "<br/>\n", $ret["text"]);
            $ret["text"] = str_replace("<note-content version=\"0.1\">" . $ret["title"] . "<br/>", "<h1>". $ret["title"] . "</h1>", $ret["text"]);
            $ret["text"] = str_replace("</note-content>", "", $ret["text"]);
            /* bold text */
            $ret["text"] = str_replace("<bold>", "<b>", $ret["text"]);
            $ret["text"] = str_replace("</bold>", "</b>", $ret["text"]);
            /* lists */
            $ret["text"] = str_replace("<list>", "<ul>", $ret["text"]);
            $ret["text"] = str_replace("</list>", "</ul>", $ret["text"]);
            /* listitems */
            $ret["text"] = str_replace("<list-item", "<li", $ret["text"]);
            $ret["text"] = str_replace("</list-item>", "</li>", $ret["text"]);

            // goji fix
            // clean url
            $ret["text"] = str_replace("<link:url>", " ", $ret["text"]);
            $ret["text"] = str_replace("</link:url>", " ", $ret["text"]);
            // datetime
            $ret["text"] = str_replace("<datetime>", "<small><i>", $ret["text"]);
            $ret["text"] = str_replace("</datetime>", "</i></small>", $ret["text"]);
            // highlight
            $ret["text"] = str_replace("<highlight>", "<span style='background:yellow;'>", $ret["text"]);
            $ret["text"] = str_replace("</highlight>", "</span>", $ret["text"]);
            // italic
            $ret["text"] = str_replace("<italic>", "<i>", $ret["text"]);
            $ret["text"] = str_replace("</italic>", "</i>", $ret["text"]);
            // underline
            $ret["text"] = str_replace("<underline>", "<u>", $ret["text"]);
            $ret["text"] = str_replace("</underline>", "</u>", $ret["text"]);
            // strikethrough
            $ret["text"] = str_replace("<strikethrough>", "<s>", $ret["text"]);
            $ret["text"] = str_replace("</strikethrough>", "</s>", $ret["text"]);
            // monospace
            $ret["text"] = str_replace("<monospace>", "<span style='font-family:monospace;'>", $ret["text"]);
            $ret["text"] = str_replace("</monospace>", "</span>", $ret["text"]);

            /* links */
            $ret["text"] = resolveInternalLinks($notes, $ret["text"]);
          }
      } 
    }
    return $ret;
  }

  function getNotes() {
    global $TOMBOY_PATH;

    $notes = array();
    $files = scandir($TOMBOY_PATH);
    foreach($files as $file) {
      $path_parts = pathinfo($file);
      if($file != "." && $file != ".." && $path_parts["extension"]=="note") {
            $note = array();
            $note["id"] = $path_parts["filename"];
            $note["rev"] = "0";
            $note["content"] = getNote($note["id"], $note["rev"], false);
            $notes[$note["id"]] = $note;
      }
    }
    return $notes;
  }

  $notes = getNotes();

?>