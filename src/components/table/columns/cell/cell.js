// Javascript Document
(()=> {
  return {
    data() {
      let db = this.closest('appui-database-db');
      return {
        project: null,
        db: null
      }
    },
    mounted() {
      this.db = this.closest('appui-database-db');
      this.project = this.closest('appui-project-ui');
    },
    computed: {
      link() {
        const db = this.db;
        if (!db) {
          return '';
        }

        let o = db.source || db;

        if (this.project) {
          return this.project.root + 'database/' + '/' + o.host + '/' + o.db + '/' + this.source.name + '/home';
        }

        return db ? db.root + 'tabs/' + o.engine + '/' + o.host + '/' + o.db + '/' + this.source.name + '/home' : ''
      }
    }
  }
})();
