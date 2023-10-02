// Javascript Document
(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-database'],
        table: this.closest('appui-database-table'),
      }
    },
    methods: {
      rename(v) {
        this.table.rename(v)
      },
      saveComment(v, ov) {
        this.table.save('comment', v, ov)
      },
      format(n){
        return bbn.fn.money(n, false, '');
      },
    }
  }
})();