@extends('client_base') <!-- Cette ligne indique d'utiliser le layout de base -->

@section('content') <!-- Ici commence le contenu spécifique à cette page -->
  
    <!-- Début de la section Héros -->
    <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-5">
            <div class="intro-excerpt">
              <h1>Paiement</h1>
            </div>
          </div>
          <div class="col-lg-7"></div>
        </div>
      </div>
    </div>
    <!-- Fin de la section Héros -->

    <div class="untree_co-section">
      <div class="container">
        
        <div class="row">
          <div class="col-md-6 mb-5 mb-md-0">
            <h2 class="h3 mb-3 text-black">Détails de facturation</h2>
            <div class="p-3 p-lg-5 border bg-white">
              <div class="form-group">
                <label for="c_country" class="text-black"
                  >Ville <span class="text-danger">*</span></label
                >
                <select id="c_country" class="form-control">
                  <option value="1">Sélectionnez un pays</option>
                  <option value="1">Casablanca</option>
                  <option value="2">Marrakech</option>
                  <option value="3">Fès</option>
                  <option value="4">Rabat</option>
                  <option value="5">Tanger</option>
                  <option value="6">Agadir</option>
                  <option value="7">Meknès</option>
                  <option value="8">Oujda</option>
                  <option value="9">Tétouan</option>
                  <option value="10">Essaouira</option>
                  <option value="11">El Jadida</option>
                  <option value="12">Nador</option>
                  <option value="13">Kénitra</option>
                  <option value="14">Laâyoune</option>
                  <option value="15">Dakhla</option>
                  <option value="16">Chefchaouen</option>
                  <option value="17">Ifrane</option>
                  <option value="18">Beni Mellal</option>
                  <option value="19">Taroudant</option>
                  <option value="20">Ouarzazate</option>
                </select>
              </div>
              <div class="form-group row">
                <div class="col-md-6">
                  <label for="c_fname" class="text-black"
                    >Prénom <span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="c_fname"
                    name="c_fname"
                  />
                </div>
                <div class="col-md-6">
                  <label for="c_lname" class="text-black"
                    >Nom <span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="c_lname"
                    name="c_lname"
                  />
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <label for="c_address" class="text-black"
                    >Adresse <span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="c_address"
                    name="c_address"
                    placeholder="Adresse"
                  />
                </div>
              </div>

              <div class="form-group mt-3">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Appartement, suite, unité, etc. (optionnel)"
                />
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <label for="c_state_country" class="text-black"
                    >Région / Département
                    <span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="c_state_country"
                    name="c_state_country"
                  />
                </div>
                <div class="col-md-6">
                  <label for="c_postal_zip" class="text-black"
                    >Code postal <span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="c_postal_zip"
                    name="c_postal_zip"
                  />
                </div>
              </div>

              <div class="form-group row mb-5">
                <div class="col-md-6">
                  <label for="c_email_address" class="text-black"
                    >Adresse e-mail <span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="c_email_address"
                    name="c_email_address"
                  />
                </div>
                <div class="col-md-6">
                  <label for="c_phone" class="text-black"
                    >Téléphone <span class="text-danger">*</span></label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="c_phone"
                    name="c_phone"
                    placeholder="Numéro de téléphone"
                  />
                </div>
              </div>

              <div class="form-group">
                <label for="c_order_notes" class="text-black"
                  >Notes de commande</label
                >
                <textarea
                  name="c_order_notes"
                  id="c_order_notes"
                  cols="30"
                  rows="5"
                  class="form-control"
                  placeholder="Écrivez vos notes ici..."
                ></textarea>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row mb-5">
              <div class="col-md-12">
                <h2 class="h3 mb-3 text-black">Votre commande</h2>
                <div class="p-3 p-lg-5 border bg-white">
                  <table class="table site-block-order-table mb-5">
                    <thead>
                      <th>Produit</th>
                      <th>Total</th>
                    </thead>
                    <tbody id="order-summary">
                      <!-- Le résumé de la commande sera ajouté ici dynamiquement -->
                    </tbody>
                  </table>

                  <div class="border p-3 mb-3">
                    <h3 class="h6 mb-0">
                      <a
                        class="d-block"
                        data-bs-toggle="collapse"
                        href="#collapsebank"
                        role="button"
                        aria-expanded="false"
                        aria-controls="collapsebank"
                        >Virement bancaire direct</a
                      >
                    </h3>

                    <div class="collapse" id="collapsebank">
                      <div class="py-2">
                        <p class="mb-0">
                          Effectuez votre paiement directement sur notre compte
                          bancaire. Veuillez utiliser votre numéro de commande
                          comme référence de paiement. Votre commande ne sera
                          pas expédiée tant que les fonds n'auront pas été reçus
                          sur notre compte.
                        </p>
                      </div>
                    </div>
                  </div>

                  

                  <div class="border p-3 mb-5">
                    <h3 class="h6 mb-0">
                      <a
                        class="d-block"
                        data-bs-toggle="collapse"
                        href="#collapsepaypal"
                        role="button"
                        aria-expanded="false"
                        aria-controls="collapsepaypal"
                        >Paypal</a
                      >
                    </h3>

                    <div class="collapse" id="collapsepaypal">
                      <div class="py-2">
                        <p class="mb-0">
                          Vous serez redirigé vers le site PayPal pour effectuer
                          le paiement en toute sécurité.
                        </p>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <button
                      class="btn btn-black btn-lg py-3 btn-block"
                      id="place-order"
                    >
                      Passer la commande
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- </form> -->
      </div>
    </div>
@endsection <!-- Ici finit le contenu spécifique à cette page -->







