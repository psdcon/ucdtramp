// Paddys day haha
(function nativeTreeWalker() {
  var node, walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
  while(node = walker.nextNode()) {
      node.nodeValue = node.nodeValue.replace(/o/, '☘');
  }
})()