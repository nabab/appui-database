// Javascript Document
(()=> {
  return {
    data(){
      let db = this.closest('appui-database-db');
      let link = db.root + 'tabs/' + db.currentData.engine + '/' + db.currentData.host + '/' + db.currentData.db + '/' + this.currentData.name + '/home';
      return {
        link: link
      }
    }
  }
})();
