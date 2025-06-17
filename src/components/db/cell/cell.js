// Javascript Document
(()=> {
  return {
    data(){
      let host = this.closest('appui-database-host');
      return {
        link:  host.root + 'tabs/' + host.currentData.engine + '/' + host.currentData.host + '/' + this.source.name + '/home'
      };
    }
  };
})();
