// Javascript Document

(() => {
  return {
    data(){
      let tmp = [];
      let table = this.closest('appui-database-table');
      bbn.fn.each(table.currentData.structure.fields, (c, name) => {
        let text = name;
        let otext = bbn.fn.getField(table.currentData.ocolumns, 'label', {code: text});
        tmp.push({value: name, label: otext || text});
      });

      return {
        table: table,
        columns: tmp
      };
    },
    computed: {
      hasDisplayedColumns() {
        return this.source.option.dcolumns && this.source.option.dcolumns.length;
      },
      displayedColumnsStr() {
        if (this.hasDisplayedColumns) {
          return this.source.option.dcolumns.join(', ') + '<br>'
        }

        return '';
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
      saveTitle(v) {
        this.table.save('title', v);
      },
      saveEditor(v) {
        this.table.save('editor', v);
      },
      saveViewer(v) {
        this.table.save('viewer', v);
      },
      saveDisplayColumns(v) {
        this.table.save('dcolumns', v);
      },
      setDisplayColumns() {
        this.getPopup({
          label: false,
          component: "appui-database-table-columns-multipicker",
          componentOptions: {
            source: this.source,
          }
        });
      },
      browse() {
        this.getPopup({
          label: "",
          component: 'appui-database-table-info',
          componentOptions: {
            db: this.table.currentData.db,
            host: this.table.host,
            engine: this.table.currentData.engine,
            table: this.table.currentData.table,
          },
        });
      },
      browseItemViewer() {
        this.getPopup({
          label: false,
          component: "appui-database-selector-viewer",
          componentEvents: {
            save: v => {
              this.saveViewer(v);
            }
          }
        });
      },
      browseRowEditor() {
        this.getPopup({
          label: false,
          component: "appui-database-selector-editor",
          componentEvents: {
            save: v => {
              this.saveEditor(v);
            }
          }
        });
      }
    }
  };
})();
