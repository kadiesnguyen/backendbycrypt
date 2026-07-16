"use client";

import type { SiteConfig, SiteConfigUpdatePayload } from "./types";

const inputClass =
  "mt-1 w-full rounded border border-border bg-surface-elevated px-3 py-2 text-sm text-foreground";

type SiteConfigFormProps = {
  config: SiteConfig | null;
  isSaving: boolean;
  saveError: string | null;
  saveSuccess: string | null;
  onSave: (payload: SiteConfigUpdatePayload) => void;
};

function Field({
  id,
  label,
  name,
  defaultValue,
  type = "text",
  placeholder,
}: {
  id: string;
  label: string;
  name: string;
  defaultValue?: string | null;
  type?: string;
  placeholder?: string;
}) {
  return (
    <div>
      <label htmlFor={id} className="block text-sm font-medium text-foreground">
        {label}
      </label>
      <input
        id={id}
        name={name}
        type={type}
        defaultValue={defaultValue ?? ""}
        placeholder={placeholder}
        className={inputClass}
      />
    </div>
  );
}

export function SiteConfigForm({
  config,
  isSaving,
  saveError,
  saveSuccess,
  onSave,
}: SiteConfigFormProps) {
  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const form = new FormData(e.currentTarget);
    const payload: SiteConfigUpdatePayload = {};

    Array.from(form.entries()).forEach(([key, value]) => {
      if (key === "webswitch") {
        payload.webswitch = Number(value);
      } else {
        (payload as Record<string, string>)[key] = String(value);
      }
    });

    onSave(payload);
  };

  return (
    <form
      onSubmit={handleSubmit}
      className="space-y-6"
      aria-label="Site configuration form"
    >
      {saveError ? (
        <div role="alert" className="rounded-lg border border-danger/40 bg-danger/10 px-4 py-3 text-sm text-danger">
          {saveError}
        </div>
      ) : null}

      {saveSuccess ? (
        <div role="status" className="rounded-lg border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">
          {saveSuccess}
        </div>
      ) : null}

      <section className="rounded-lg border border-border bg-surface p-4">
        <h2 className="text-sm font-medium text-foreground">General</h2>
        <p className="mt-1 text-xs text-muted">Site name, title, and maintenance switch.</p>
        <div className="mt-4 grid gap-4 sm:grid-cols-2">
          <Field id="webname" label="Site name" name="webname" defaultValue={config?.webname} />
          <Field id="webtitle" label="Site title" name="webtitle" defaultValue={config?.webtitle} />
          <div>
            <label htmlFor="webswitch" className="block text-sm font-medium text-foreground">
              Site switch
            </label>
            <select
              id="webswitch"
              name="webswitch"
              defaultValue={String(config?.webswitch ?? 1)}
              className={inputClass}
            >
              <option value="1">Open</option>
              <option value="2">Maintenance</option>
            </select>
          </div>
        </div>
      </section>

      <section className="rounded-lg border border-border bg-surface p-4">
        <h2 className="text-sm font-medium text-foreground">Bank details</h2>
        <p className="mt-1 text-xs text-muted">Fiat deposit bank account information.</p>
        <div className="mt-4 grid gap-4 sm:grid-cols-2">
          <Field id="bank_name" label="Bank name" name="bank_name" defaultValue={config?.bank_name} />
          <Field id="bank_acc_no" label="Account number" name="bank_acc_no" defaultValue={config?.bank_acc_no} />
          <Field
            id="bank_acc_name"
            label="Account holder"
            name="bank_acc_name"
            defaultValue={config?.bank_acc_name}
          />
        </div>
      </section>

      <section className="rounded-lg border border-border bg-surface p-4">
        <h2 className="text-sm font-medium text-foreground">Web assets</h2>
        <p className="mt-1 text-xs text-muted">Desktop logos, sliders, and feature images (file paths).</p>
        <div className="mt-4 grid gap-4 sm:grid-cols-2">
          <Field id="weblogo" label="Web logo" name="weblogo" defaultValue={config?.weblogo} />
          <Field id="waplogo" label="Mobile logo" name="waplogo" defaultValue={config?.waplogo} />
          <Field id="websildea" label="Slider A" name="websildea" defaultValue={config?.websildea} />
          <Field id="websildeb" label="Slider B" name="websildeb" defaultValue={config?.websildeb} />
          <Field id="websildec" label="Slider C" name="websildec" defaultValue={config?.websildec} />
          <Field id="webissue" label="Issue banner" name="webissue" defaultValue={config?.webissue} />
          <Field id="webkj" label="Mining banner" name="webkj" defaultValue={config?.webkj} />
          <Field id="webtjimgs" label="Recommend images" name="webtjimgs" defaultValue={config?.webtjimgs} />
        </div>
      </section>

      <section className="rounded-lg border border-border bg-surface p-4">
        <h2 className="text-sm font-medium text-foreground">Mobile assets</h2>
        <p className="mt-1 text-xs text-muted">Mobile sliders and feature images (file paths).</p>
        <div className="mt-4 grid gap-4 sm:grid-cols-2">
          <Field id="wapsildea" label="Mobile slider A" name="wapsildea" defaultValue={config?.wapsildea} />
          <Field id="wapsildeb" label="Mobile slider B" name="wapsildeb" defaultValue={config?.wapsildeb} />
          <Field id="wapsildec" label="Mobile slider C" name="wapsildec" defaultValue={config?.wapsildec} />
          <Field id="wapsilded" label="Mobile slider D" name="wapsilded" defaultValue={config?.wapsilded} />
          <Field id="wapissue" label="Mobile issue banner" name="wapissue" defaultValue={config?.wapissue} />
          <Field id="wapkj" label="Mobile mining banner" name="wapkj" defaultValue={config?.wapkj} />
          <Field id="waptjimgs" label="Mobile recommend images" name="waptjimgs" defaultValue={config?.waptjimgs} />
        </div>
      </section>

      <div className="flex justify-end">
        <button
          type="submit"
          disabled={isSaving}
          className="rounded bg-primary px-4 py-2 text-sm font-medium text-background transition hover:opacity-90 disabled:opacity-40"
        >
          {isSaving ? "Saving…" : "Save configuration"}
        </button>
      </div>
    </form>
  );
}
