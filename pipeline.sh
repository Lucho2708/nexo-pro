#!/bin/bash
# =====================================================================
# NEXO PRO - Sistema de Orquestación Agéntica con Feedback Loops
# =====================================================================

PROMPT_CLIENTE=$1
MAX_RETRIES=3
CONFIG_DIR="$HOME/.config/antigravity/squad"

if [ -z "$PROMPT_CLIENTE" ]; then
    echo "❌ Error: Debes ingresar el requerimiento del cliente."
    exit 1
fi

echo "====================================================================="
echo "🚀 INICIANDO FÁBRICA DE SOFTWARE NEXO PRO"
echo "====================================================================="

# ---------------------------------------------------------------------
# FASE 1: CONCEPCIÓN Y ALCANCE (Agentes 10 y 1)
# ---------------------------------------------------------------------
echo "🔍 [1/6] Lanzando Inteligencia de Mercado Comercial..."
agy --config "$CONFIG_DIR/10_market_researcher.yaml" "Investiga tendencias, competidores y da ideas para: $PROMPT_CLIENTE"

echo "📋 [2/6] Definiendo Requerimientos y MVP (Product Owner)..."
agy --config "$CONFIG_DIR/1_product_owner.yaml" "Lee market_research.md y estructura product_requirements.md con Historias de Usuario Gherkin"

# ---------------------------------------------------------------------
# FASE 2: DISEÑO ARQUITECTÓNICO Y PERSISTENCIA (Agentes 2 y 3)
# ---------------------------------------------------------------------
echo "🏗️  [3/6] Diseñando Arquitectura de Alto Rendimiento (Agnóstica)..."
agy --config "$CONFIG_DIR/2_architect.yaml" "Lee product_requirements.md y genera architecture_spec.md resolviendo cuellos de botella de I/O y CPU"

echo "🗄️  [4/6] Modelando Base de Datos (Especializada y en Español)..."
agy --config "$CONFIG_DIR/3_db_dev.yaml" "Lee architecture_spec.md, categoriza tablas (Transaccional/Paramétrica), indexa y genera database/schema/db_specification.md sin caracteres especiales"

# ---------------------------------------------------------------------
# FASE 3: DESARROLLO DE CÓDIGO (Agentes 4 y 5)
# ---------------------------------------------------------------------
echo "💻 [5/6] Escribiendo Lógica de Negocio Backend..."
agy --config "$CONFIG_DIR/4_backend_dev.yaml" "Lee architecture_spec.md y db_specification.md. Escribe código modular en src/backend/ con Documentación Bifurcada"

# LOOP DE RETROALIMENTACIÓN INTEGRADO (BACKEND 🔄 SOLID VALIDATOR)
ESTADO_SOLID="RECHAZADO"
INTENTO_SOLID=1

while [ "$ESTADO_SOLID" != "APROBADO" ] && [ $INTENTO_SOLID -le $MAX_RETRIES ]; do
    echo "   ⚖️  [Loop SOLID] Evaluando calidad de código (Intento $INTENTO_SOLID/$MAX_RETRIES)..."
    agy --config "$CONFIG_DIR/9_solid_validator.yaml" "Audita src/backend/ bajo los 5 principios SOLID. Escribe solid_audit.md y termina el archivo ESTRICTAMENTE con la palabra 'APROBADO' si está limpio o 'RECHAZADO' si requiere cambios."

    if grep -q "APROBADO" solid_audit.md; then
        ESTADO_SOLID="APROBADO"
        echo "   ✅ Código aprobado por el Validador SOLID."
    else
        echo "   ⚠️ Violación arquitectónica detectada. Devolviendo código al Backend..."
        agy --config "$CONFIG_DIR/4_backend_dev.yaml" "Refactoriza src/backend/. Corrige las violaciones descritas en solid_audit.md manteniendo la Documentación Bifurcada."
        ((INTENTO_SOLID++))
    fi
done

echo "🎨 [6/6] Desarrollando Interfaz de Usuario Frontend (UX/UI)..."
agy --config "$CONFIG_DIR/5_frontend.yaml" "Crea los componentes interactivos de UI consumiendo el backend, aplicando tokens de color, modo claro/oscuro y WCAG"

# ---------------------------------------------------------------------
# FASE 4: ASEGURAMIENTO DE CALIDAD, GIT Y SEGURIDAD (Agentes 6, 8 y 7)
# ---------------------------------------------------------------------
ESTADO_CALIDAD="FALLIDO"
INTENTO_QA=1

while [ "$ESTADO_CALIDAD" != "PASADO" ] && [ $INTENTO_QA -le $MAX_RETRIES ]; do
    echo "====================================================================="
    echo "🛡️  INICIANDO CICLO DE INTEGRACIÓN Y AUTO-SANACIÓN (Intento $INTENTO_QA/$MAX_RETRIES)"
    echo "====================================================================="

    echo "👔 Senior Reviewer: Evaluando integración Full-Stack y Gobernanza Git..."
    agy --config "$CONFIG_DIR/6_senior_reviewer.yaml" "Audita la cohesión entre capas y revisa que los mensajes de commit cumplan con Conventional Commits. Genera code_review_report.md."

    echo "🚨 Security Sec: Escaneando vulnerabilidades (Threat Intelligence) y secretos..."
    agy --config "$CONFIG_DIR/8_security_sec.yaml" "Analiza dependencias y código contra CVEs recientes y OWASP. Genera security_audit.md. Si hay riesgos críticos escribe 'BLOQUEADO PARA PRODUCCIÓN'."

    echo "🧪 QA Engineer: Ejecutando suite de pruebas automatizadas..."
    agy --config "$CONFIG_DIR/7_qa_engineer.yaml" "Valida el código final frente a los criterios de product_requirements.md. Escribe qa_test_report.md y finaliza el archivo ESTRICTAMENTE con 'PASADO' o 'FALLIDO'."

    # Evaluación de compuertas de calidad cruzadas
    if grep -q "PASADO" qa_test_report.md && ! grep -q "BLOQUEADO PARA PRODUCCIÓN" security_audit.md; then
        ESTADO_CALIDAD="PASADO"
        echo "🎉 ¡CÓDIGO PERFECTO! El desarrollo ha superado todas las pruebas de NEXO PRO."
    else
        echo "❌ Falla detectada en Calidad o Seguridad. Disparando retroalimentación de auto-sanación..."
        
        # Retroalimentación directa enviando los reportes combinados al Backend y Frontend
        agy --config "$CONFIG_DIR/4_backend_dev.yaml" "REPARACIÓN EN CALIENTE BUGS: Revisa code_review_report.md, security_audit.md y qa_test_report.md. Corrige inmediatamente los fallos de lógica, vulnerabilidades o excepciones."
        agy --config "$CONFIG_DIR/5_frontend.yaml" "REPARACIÓN EN CALIENTE INTERFAZ: Si hay fallos en la UI reportados en qa_test_report.md o code_review_report.md, soluciónalos inmediatamente."
        
        ((INTENTO_QA++))
    fi
done

if [ "$ESTADO_CALIDAD" != "PASADO" ]; then
    echo "⚠️ El pipeline alcanzó el límite de intentos. Se requiere intervención del Líder Técnico Humano."
    exit 1
fi
