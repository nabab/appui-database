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
      },
      options: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      save(data) {
        if (bbn.fn.isObject(data)) {
          const url = this.root + (!this.root.endsWith('/') ? '/' : '') + 'actions/column/' + (data.name?.length ? 'update' : 'create');
          this.post(url, data, d => {
            if (d.success) {
              this.$emit('success', d);
              appui.success(bbn._("The column has been saved"));
              this.getPopup().close();
            }
          });
        }
        else {
          appui.error(bbn._("Invalid data"));
        }
      },
      onSubmit(ev, data, originalData) {
        ev.preventDefault();
        let obj = {
          db: this.db,
          host: this.host,
          engine: this.engine,
          table: this.table,
          name: originalData.name,
        };
        this.post(
          this.root + (!this.root.endsWith('/') ? '/' : '') + "actions/column/validform",
          obj,
          d => {
            if (d.success) {
              obj.data = bbn.fn.clone(data);
              obj.options = data.options || false;
              if (obj.data.options !== undefined) {
                delete obj.data.options;
              }

              if (d.num) {
                this.confirm(bbn._("The column holds data which might get corrupted by this change"), () => {
                  this.save(obj);
                });
              }
              else {
                this.save(obj);
              }
            }
          }
        );
      },
      onCancel() {
        this.getPopup().close();
      }
    }
  };
})();
