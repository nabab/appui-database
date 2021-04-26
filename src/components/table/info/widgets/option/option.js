// Javascript Document

(() => {
  return {
    data(){
      let tmp = [];
      let table = this.closest('appui-database-table');
      bbn.fn.each(table.source.structure.fields, (c, name) => {
        let text = name;
        let otext = bbn.fn.getField(table.source.ocolumns, 'text', {code: text});
        tmp.push({value: name, text: otext || text});
      });

      return {
        table: table,
        columns: tmp
      }
    },
    methods: {
      editEditor(ev, editableCp) {
        ev.preventDefault();
        this.getPopup().load({
          url: appui.plugins['appui-core'] + "/component/picker",
          width: 350,
          height: 500,
          source: {value: ''}
        });
      },
      saveTitle(v, ov) {
        this.table.save('title', v, ov)
      },
      saveItemComponent(v, ov) {
        this.table.save('itemComponent', v, ov)
      },
      saveEditor(v, ov) {
        this.table.save('editor', v, ov)
      },
      saveDisplayColumns(v, ov) {
        this.table.save('dcolumns', v, ov)
      }
    }
  }
})();