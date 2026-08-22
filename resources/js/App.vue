<script setup>
import { ref, onMounted } from 'vue';

const reservations = ref([]);
const loading = ref(false);
const message = ref('');
const editingId = ref(null);

const defaultForm = () => ({
  requester_name: '',
  laptop_asset_number: '',
  student_class: '',
  teacher_name: '',
  subject: '',
  includes_charger: false,
  charger_code: '',
});

const form = ref(defaultForm());
const API_URL = '/api/reservations';

const fetchReservations = async () => {
  try {
    const res = await fetch(API_URL);
    if (res.ok) {
      reservations.value = await res.json();
    }
  } catch (error) {
    console.error('Erro ao buscar reservas:', error);
  }
};

const saveReservation = async () => {
  loading.value = true;
  message.value = '';

  const url = editingId.value ? `${API_URL}/${editingId.value}` : API_URL;
  const method = editingId.value ? 'PUT' : 'POST';

  try {
    const res = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(form.value),
    });

    if (res.ok) {
      message.value = editingId.value ? 'Reserva atualizada com sucesso!' : 'Reserva cadastrada com sucesso!';
      cancelEdit();
      await fetchReservations();
    } else {
      const err = await res.json();
      message.value = err.message || 'Erro ao processar reserva.';
    }
  } catch (error) {
    message.value = 'Falha na conexão com o servidor.';
  } finally {
    loading.value = false;
  }
};

const editReservation = (item) => {
  editingId.value = item.id;
  form.value = {
    requester_name: item.requester_name,
    laptop_asset_number: item.laptop_asset_number,
    student_class: item.student_class,
    teacher_name: item.teacher_name,
    subject: item.subject,
    includes_charger: Boolean(item.includes_charger),
    charger_code: item.charger_code || '',
  };
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const cancelEdit = () => {
  editingId.value = null;
  form.value = defaultForm();
};

const deleteReservation = async (id) => {
  if (!confirm('Deseja realmente excluir esta reserva?')) return;

  try {
    const res = await fetch(`${API_URL}/${id}`, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
      },
    });

    if (res.ok) {
      message.value = 'Reserva excluída com sucesso!';
      if (editingId.value === id) cancelEdit();
      await fetchReservations();
    } else {
      message.value = 'Erro ao excluir reserva.';
    }
  } catch (error) {
    message.value = 'Falha ao conectar com o servidor.';
  }
};

onMounted(() => {
  fetchReservations();
});
</script>

<template>
  <div class="container">
    <h1>Controle de Reservas de Notebooks</h1>

    <div v-if="message" class="alert">{{ message }}</div>

    <form @submit.prevent="saveReservation" class="form-card">
      <div class="form-header">
        <h2>{{ editingId ? 'Editar Reserva' : 'Nova Reserva' }}</h2>
        <button v-if="editingId" type="button" @click="cancelEdit" class="btn-cancel">Cancelar Edição</button>
      </div>

      <div class="grid">
        <div class="form-group">
          <label>Nome do Solicitador *</label>
          <input v-model="form.requester_name" type="text" required placeholder="Ex: João Silva" />
        </div>

        <div class="form-group">
          <label>Patrimônio do Notebook *</label>
          <input v-model="form.laptop_asset_number" type="text" required placeholder="Ex: NTB-042" />
        </div>

        <div class="form-group">
          <label>Turma do Estudante *</label>
          <input v-model="form.student_class" type="text" required placeholder="Ex: 3º Ano A" />
        </div>

        <div class="form-group">
          <label>Professor Responsável *</label>
          <input v-model="form.teacher_name" type="text" required placeholder="Ex: Prof. Carlos" />
        </div>

        <div class="form-group full-width">
          <label>Disciplina *</label>
          <input v-model="form.subject" type="text" required placeholder="Ex: Banco de Dados" />
        </div>
      </div>

      <div class="toggle-section">
        <label class="toggle-label">
          <input type="checkbox" v-model="form.includes_charger" />
          <span>Incluir Carregador?</span>
        </label>
      </div>

      <div v-if="form.includes_charger" class="form-group highlight">
        <label>Código do Carregador *</label>
        <input 
          v-model="form.charger_code" 
          type="text" 
          required 
          placeholder="Ex: CAR-042" 
        />
      </div>

      <button type="submit" :disabled="loading" class="btn-primary">
        {{ loading ? 'Salvando...' : (editingId ? 'Salvar Alterações' : 'Confirmar Reserva') }}
      </button>
    </form>

    <div class="list-card">
      <h2>Reservas Cadastradas</h2>
      <table v-if="reservations.length > 0">
        <thead>
          <tr>
            <th>Solicitador</th>
            <th>Notebook</th>
            <th>Turma</th>
            <th>Professor / Disciplina</th>
            <th>Carregador</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in reservations" :key="item.id">
            <td><strong>{{ item.requester_name }}</strong></td>
            <td>{{ item.laptop_asset_number }}</td>
            <td>{{ item.student_class }}</td>
            <td>{{ item.teacher_name }} ({{ item.subject }})</td>
            <td>
              <span v-if="item.includes_charger" class="tag-yes">Sim ({{ item.charger_code }})</span>
              <span v-else class="tag-no">Não</span>
            </td>
            <td class="actions-cell">
              <button @click="editReservation(item)" class="btn-action edit">Editar</button>
              <button @click="deleteReservation(item.id)" class="btn-action delete">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
      <p v-else class="empty-state">Nenhuma reserva registrada até o momento.</p>
    </div>
  </div>
</template>

<style scoped>
.container {
  max-width: 900px;
  margin: 40px auto;
  padding: 0 16px;
  font-family: system-ui, -apple-system, sans-serif;
  color: #1f2937;
}

h1 {
  text-align: center;
  margin-bottom: 24px;
  font-size: 24px;
}

h2 {
  font-size: 18px;
  margin-bottom: 0;
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 8px;
}

.form-card, .list-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 24px;
  margin-bottom: 24px;
}

.grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: span 2;
}

.form-group.highlight {
  margin-top: 12px;
  background: #f8fafc;
  padding: 12px;
  border-radius: 6px;
  border-left: 4px solid #2563eb;
}

label {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 6px;
}

input[type="text"] {
  padding: 10px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
}

.toggle-section {
  margin: 16px 0;
}

.toggle-label {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.toggle-label input {
  width: 18px;
  height: 18px;
  cursor: pointer;
}

.btn-primary {
  width: 100%;
  background: #2563eb;
  color: #fff;
  border: none;
  padding: 12px;
  font-size: 15px;
  font-weight: bold;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 16px;
}

.btn-primary:hover {
  background: #1d4ed8;
}

.btn-cancel {
  background: #ef4444;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
  font-weight: bold;
}

.alert {
  background: #dbeafe;
  color: #1e40af;
  padding: 12px;
  border-radius: 6px;
  margin-bottom: 16px;
  text-align: center;
  font-weight: 500;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 12px;
}

th, td {
  padding: 10px;
  border-bottom: 1px solid #e5e7eb;
  text-align: left;
  font-size: 14px;
}

th {
  background: #f9fafb;
  font-weight: 600;
}

.tag-yes {
  background: #dcfce7;
  color: #166534;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
}

.tag-no {
  background: #f3f4f6;
  color: #4b5563;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 12px;
}

.actions-cell {
  display: flex;
  gap: 8px;
}

.btn-action {
  padding: 4px 8px;
  border: none;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  font-weight: 600;
}

.btn-action.edit {
  background: #fef08a;
  color: #854d0e;
}

.btn-action.delete {
  background: #fee2e2;
  color: #991b1b;
}

.empty-state {
  color: #6b7280;
  text-align: center;
  margin-top: 16px;
}
</style>
