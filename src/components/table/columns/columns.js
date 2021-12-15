( () => {
  return {
    props: ['source'],
    computed: {
      tableSource(){
        let r = [];
        bbn.fn.iterate(this.source.structure.fields, (a, n) => {
          r.push(bbn.fn.extend({name: n}, a));
        });
        return r;
      }
    },
    methods: {
      getStateColor(row) {
        let col = false;
        if (!row.is_real) {
          col = 'red';
        } else if (!row.virtual) {
          col = 'purple';
        } else if (row.is_same) {
          col = 'green';
        }
        return col;
      },
      writeKeyInCol(row) {
        if (!row.key) {
          return ' ';
        }
        return '<i class="nf nf-fa-key ' + (row.key === 'PRI' ? 'bbn-yellow' : 'bbn-grey') + '"> </i>';
      },
      writeType(row) {
        if (row.type === 'int') {
          row.type += ' (<em>' + (row.signed ? '' : 'un') + 'signed)</em>';
        }
        return row.type;
      },
      writeColumn(row) {
        let col = this.getStateColor(row);
        let st = '<a' + (col ? ' class="bbn-' + col + '"' : '') + '>' + row.name + '</a>';
        if (this.source.constraints[row.name]) {
          st += ' (' + bbn._('refers to') + ' ' + this.source.constraints[row.name].column + ' ' + bbn._('in') + ' ' + this.source.constraints[row.name].table + ')';
        }
        return st;
      },
      writeNull(row) {
        return row.null ? '<i class="nf nf-fa-check"> </i>' : ' ';
      },
      writeDefault(row) {
        return row.default || '-';
      },
      remove() {
        return;
      },
      update(data, col, idx) {
        let cp = this.closest('bbn-container');
        let cp2 = cp.closest("bbn-container").getComponent();
        let cp3 = cp2.closest('bbn-container').getComponent();
        bbn.fn.log("cp = ", data);
        data.olddecimal = data.decimal;
        data.olddefault = data.default;
        data.olddefaultExpression = data.defaultExpression;
        data.oldname = data.name;
        data.oldtype = data.type;
        data.oldsigned = data.signed;
        data.oldnull = data.null;
        data.oldkey = data.key;
        data.oldmaxlength = data.maxlength;
        data.oldindex = data.index;
        this.getPopup({
          title: 'Edit a column',
          component: 'appui-database-column-editor',
          source : {
            db: cp.source.db,
            host: cp.source.host,
            engine: cp.source.engine,
            table: cp.source.table,
            otypes:  appui.databases.source.mysql.types,
            predefined:  appui.databases.source.mysql.predefined,
            source: data,
            root: appui.databases.source.mysql.root,
          },
        })
        bbn.fn.log('componentOptions =', cp.source.db, cp.source.host, cp.source.engine, cp.source.table, "data et le reste:", data, col, idx);
        return;
      },
      moveUp(idx) {
        if (idx.position > 1) {
          let tmp = idx.position - 1;
          bbn.fn.move(this.tableSource, tmp, tmp - 1);
          bbn.fn.log('idx + tableSource', idx, this.tableSource);
        }
        return;
      },
      moveDown (idx) {
        if (idx.position < this.tableSource.length) {
          let tmp = idx.position - 1;
          bbn.fn.move(this.tableSource, tmp, tmp + 1);
          bbn.fn.moveColumn(this.tableSource, tmp, tmp + 1);
          bbn.fn.log('ca existe ?', idx, this.tableSource);
        }
        return;
      },
      changeColPosition() {
        return;
      },
      makePredefined() {
        return;
      },
      addKey() {
        return;
      },
    },
  }
})();