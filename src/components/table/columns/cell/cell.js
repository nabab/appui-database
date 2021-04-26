// Javascript Document
(()=> {
  return {
    data(){
      let db = this.closest('appui-database-db');
      let link = db.root + 'tabs/' + db.source.engine + '/' + db.source.host + '/' + db.source.db + '/' + this.source.name + '/home';
      return {
        link: link
      }
    }
  }
})();