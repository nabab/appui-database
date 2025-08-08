(() => {
  return {
    props: {
      source: {
        type: Object,
        required: true,
      },
      otypes: {
        type: Array
      },
      engine: {
        type: String
      },
      host: {
        type: String
      },
      db: {
        type: String
      },
      table: {
        type: String
      },
      predefined: {
        type: Array
      },
      root: {
        type: String,
        default: appui.plugins["appui-database"] + '/'
      },
      columns: {
        type: Array,
        default(){
          return [];
        }
      }
    },
    methods: {
      update() {
        let i = 1;
        let path = appui.plugins["appui-database"] + "/" + "actions/column/update";
        bbn.fn.post(path, {
          data: this.source,
          db: this.db,
          host: this.host,
          engine: this.engine,
          table: this.table,
          name: this.getRef('form').originalData.name,
        }, d => {
          if (d.success) {
            this.getPopup().close();
          }
        });
      },
      /*
      * This method is submitting changes in a column in a table inside a database
      */
      submit() {
        /*if (this.source.source.oldtype === this.source.source.type) {
          return this.update();
        }*/
        let path = appui.plugins["appui-database"] + "/" + "actions/column/validform";
        let data = {
          db: this.db,
          host: this.host,
          engine: this.engine,
          table: this.table,
          name: this.getRef('form').originalData.name,
        };
        bbn.fn.post(path, data, (d) => {
          if (d.success) {
            if (d.num) {
              this.confirm(bbn._("The column holds data which might get corrupted by this change"), () => {
                this.update();
              });
            }
            else {
              this.update();
            }
          }
        });
      },
      cancel () {
        this.$refs.form.cancel();
      },
    },
    watch: {
      "source.source": {
        deep: true,
        handler() {
          this.$refs.colform.checkColumnsNames();
        }
      }
    }
  };
})();
