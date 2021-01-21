// Javascript Document
(()=> {
  return {
    data(){
      let host = this.closest('appui-database-host-page');
      let link = host.root + 'tabs/' + host.source.engine + '/' + host.source.info.code + '/' + this.source.name + '/home';
      return {
        link: link
      }
    }
  }
})();