(() => {
  return {
    data(){
      let tmp = [];
      bbn.fn.each(this.source.structure.fields, (c, name) => {
        let text = name;
        let otext = bbn.fn.getField(this.source.ocolumns, 'label', {code: text});
        tmp.push({value: name, label: otext || text});
      });

      return {
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
        this.main.save('title', v);
      },
      saveEditor(v) {
        this.main.save('editor', v);
      },
      saveViewer(v) {
        this.main.save('viewer', v);
      },
      saveDisplayColumns(v) {
        this.main.save('dcolumns', v);
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
            db: this.main.currentData.database,
            host: this.main.currentData.host,
            engine: this.main.currentData.engine,
            table: this.main.currentData.name,
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
