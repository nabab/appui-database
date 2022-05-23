// Javascript Document


(()=>{
  const defaultColumn = {
    name: "",
    maxlength: null,
    decimals: null,
    type: 'varchar',
    defaultExpression: 0,
    default: null,
    extra: '',
    signed: 1,
    "null": 0,
    ref_table: '',
    ref_column: '',
    index: '',
    delete:'CASCADE',
    update:'CASCADE'
  };
  return {
    props: ['source'],
    data () {
      return {
        root: appui.plugins["appui-database"] + '/',
        name: '',
        comment: '',
        edited: -1,
        formData: {
          name: this.name,
          comment: this.comment,
          columns: []
        },
        keys: [],
        cols: []
      };
    },
    computed: {
      numMovableColumns() {
        let tmp = this.formData.columns.length;
        if (this.edited !== -1) {
          tmp--;
        }
        return tmp;
      },
      tableData() {
        let res = {
          db: this.source.db,
          host: this.source.host,
          engine: this.source.engine,
          keys: {},
          cols: {},
          fields: {}
        };
        const excluded = ['name', 'index', 'ref_table', 'ref_column'];
        bbn.fn.each(this.formData.columns, a => {
          res.fields[a.name] = {};
          for (let n in a) {
            if (!excluded.includes(n)) {
              res.fields[a.name][n] = a[n];
            }
          }
          if (a.index) {
            let kn = a.index === 'primary' ? 'PRIMARY' : a.name;
            res.cols[a.name] = [kn];
            res.fields[a.name].key = a.index === 'primary' ? 'PRI' : 'MUL';
            res.keys[kn] = {
              columns: [
                a.name
              ],
              unique: ['primary', 'unique'].includes(a.index) ? 1 : 0
            };

            if (a.ref_column) {
              res.keys[kn].ref_db = this.source.db;
              res.keys[kn].ref_table = a.ref_table;
              res.keys[kn].ref_column = a.ref_column;
              res.keys[kn].update = this.update;
              res.keys[kn].delete = this.delete;
            }
          }
        });
        return res;
      },
    },
    methods: {
      onCreate(d) {
        if (d.success) {
          let table = this.closest('bbn-floater').opener.getRef('table');
          if (table) {
            table.updateData();
          }

          bbn.fn.link(this.root+ 'tabs/' + this.source.engine + '/' + this.source.host + '/' + this.source.db + '/' + this.formData.name + '/columns');
          this.getRef('form').closePopup();
        }
      },
      removeColumn(idx) {
        this.confirm(bbn._("Are you sure you want to remove this column?"), () => {
          this.formData.columns.splice(idx, 1);
        })
        if (this.formData.columns[this.edited]) {
          if (this.edited === idx) {
            this.edited = -1;
          }
          else if (this.edited > idx) {
            this.edited--;
          }
        }
      },
      addColumn(idx, cfg) {
        bbn.fn.log("Add column", idx, cfg);
        if (this.formData.columns[idx]) {
          this.formData.columns.splice(idx, 0, bbn.fn.extend({}, defaultColumn, cfg || {}));
        }
        else {
          this.formData.columns.push(bbn.fn.extend({}, defaultColumn, cfg || {}));
          idx = this.formData.columns.length - 1;
        }

        this.edited = idx;
      },
      onChange() {
        this.edited = -1;
        this.$nextTick(() => {
          this.getRef('form').update();
        });
      },
      onCancel(o) {
        if (o) {
          this.formData.columns.splice(this.edited, 1, o);
        }
        else {
          this.formData.columns.splice(this.edited, 1);
        }
        this.edited = -1;
      },
      getColDescription(col) {
        let str = '<strong>' + col.name + '</strong> ' + col.type;
        if (col.maxlength) {
          str += " (" + col.maxlength;
          if (col.decimals) {
            str += ', ' + col.decimals;
          }
          str += ")";
        }
        if (col.signed === 0) {
          str += ' UNSIGNED';
        }

        if (!col.null) {
          str += " NOT NULL";
        }

        if (col.defaultExpression || col.default) {
          str += " DEFAULT ";
          if (col.default === null) {
            str += "NULL";
          }
          else if (!col.defaultExpression && bbn.fn.isString(col.default)) {
            str += '"' + bbn.fn.replaceAll('"', '\\"', col.default) + '"';
          }
          else {
            str += col.default;
          }
        }
        return str;
      },
      moveUp(idx) {
        if (this.formData.columns[idx] && this.formData.columns[idx - 1]) {
          bbn.fn.move(this.formData.columns, idx, idx - 1);
        }
      },
      moveDown(idx) {
        if (this.formData.columns[idx] && this.formData.columns[idx + 1]) {
          bbn.fn.move(this.formData.columns, idx, idx + 1);
        }
      },
    },
    watch: {
      values (v) {
        if (!v.length) {
          this.source.extra = "";
        }
        else {
          this.source.extra = v.map(a => {
            return "'" + bbn.fn.replaceAll(a, "'", "\\'") + "'";
          }).join(",");
        }
      },
    }
  };
})();